<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">

    <div class="container col-md-8 col-lg-6"
         style="box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px; padding: 0;">

        <div class="row g-0">

            <!-- LEFT SIDE -->
            <div class="col-md-4 d-flex flex-column justify-content-center text-center"
                 style="background-color: #23c984; border-radius: 8px 0 0 8px; padding: 20px;">

                <h3 class="m-0">Welcome Back!</h3>
                <p class="m-0">Please login to your account</p>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-8 p-4">

                <img src="https://example.com/logo.png"
                     alt="Company Logo"
                     class="d-block mx-auto mb-3"
                     style="max-width: 150px;">

                <h2 class="text-center mb-4">Login</h2>

                <div id="alertContainer"></div>

                <form id="loginForm">

                    <div class="mb-3">
                        <input type="email" id="email" name="email"
                               class="form-control"
                               placeholder="Email" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" id="password" name="password"
                               class="form-control"
                               placeholder="Password" required>
                    </div>

                    <button type="submit" id="loginBtn" class="btn btn-success w-100">
                        Login
                    </button>
                </form>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const alertContainer = document.getElementById('alertContainer');

            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    // Store token in localStorage
                    localStorage.setItem('authToken', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    // Show success message
                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Login successful. Redirecting...
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                    // Redirect after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    // Show error message
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> ${data.message || 'Login failed. Please try again.'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                    // Reset button
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = 'Login';
                }
            } catch (error) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${error.message || 'An error occurred. Please try again.'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Reset button
                loginBtn.disabled = false;
                loginBtn.innerHTML = 'Login';
            }
        });
    </script>

</body>
</html>

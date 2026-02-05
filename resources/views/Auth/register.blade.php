<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height:100vh;">

<div class="container col-md-8 col-lg-6"
     style="box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:8px; padding:0;">

    <div class="row g-0">

        <div class="col-md-8 p-4">

            <h2 class="text-center mb-4">Register</h2>

            <div id="alertContainer"></div>

            <form id="registerForm">

                <div class="mb-3">
                    <input type="text" id="name" name="name"
                           class="form-control"
                           placeholder="Nama" required>
                </div>

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

                <div class="mb-3">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control"
                           placeholder="Konfirmasi Password" required>
                </div>

                <button type="submit" id="registerBtn" class="btn btn-success w-100">
                    Register
                </button>
            </form>

        </div>

        <div class="col-md-4 d-flex flex-column justify-content-center text-center text-white"
             style="background-color:#23c984; border-radius:0 8px 8px 0; padding:20px;">

            <h3 class="m-0">Hello!</h3>
            <p class="m-0">Create your account to get started</p>

            <div class="mt-4">
                <p class="mb-2 small">Already have an account?</p>
                <a href="/login" class="btn btn-outline-light w-75 rounded-pill">Login</a>
            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const registerBtn = document.getElementById('registerBtn');
        const alertContainer = document.getElementById('alertContainer');

        // Validate passwords match
        if (password !== password_confirmation) {
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Passwords do not match.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            return;
        }

        // Show loading state
        registerBtn.disabled = true;
        registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                    password_confirmation: password_confirmation,
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
                        <strong>Success!</strong> Registration successful. Redirecting...
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Redirect after 1.5 seconds
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            } else {
                // Show error message
                let errorMsg = data.message || 'Registration failed. Please try again.';
                
                // Handle validation errors
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('<br>');
                }

                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${errorMsg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Reset button
                registerBtn.disabled = false;
                registerBtn.innerHTML = 'Register';
            }
        } catch (error) {
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ${error.message || 'An error occurred. Please try again.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            // Reset button
            registerBtn.disabled = false;
            registerBtn.innerHTML = 'Register';
        }
    });
</script>

</body>
</html>
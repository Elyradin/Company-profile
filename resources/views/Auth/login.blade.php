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

            <div class="col-md-4 d-flex flex-column justify-content-center text-center"
                 style="background-color: #23c984; border-radius: 8px 0 0 8px; padding: 20px;">
                <h3 class="m-0 text-white">Welcome Back!</h3>
                <p class="m-0 text-white">Please login to your account</p>
            </div>

            <div class="col-md-8 p-4">

                <div class="text-center mb-3">
                    <h2 class="fw-bold">Login</h2>
                </div>

                <div id="alertContainer"></div>

                <form id="loginForm">
                    <div class="mb-3">
                        <input type="email" name="email" id="email" 
                               class="form-control"
                               placeholder="Email" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" id="password"
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
        e.preventDefault(); // Mencegah reload halaman

        // Ambil elemen berdasarkan ID (Sekarang ID-nya sudah ada di HTML)
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const btn = document.getElementById('loginBtn');
        const alertBox = document.getElementById('alertContainer');

        const email = emailInput.value;
        const password = passwordInput.value;

        // 1. Reset Tampilan Tombol & Alert
        alertBox.innerHTML = '';
        btn.disabled = true;
        const originalBtnText = btn.innerText;
        btn.innerText = 'Loading...';

        try {
            // 2. Kirim Request ke API
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                // 3. Login Sukses: Simpan Token & Data User
                localStorage.setItem('authToken', data.token);
                localStorage.setItem('userData', JSON.stringify(data.user));

                // 4. Redirect sesuai Role
                if (data.user.role === 'admin') {
                    window.location.href = '/admin/dashboard';
                } else {
                    window.location.href = '/dashboard';
                }
            } else {
                // 5. Login Gagal
                alertBox.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${data.message || 'Login Gagal'}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                      </div>`;
                btn.disabled = false;
                btn.innerText = originalBtnText;
            }
        } catch (error) {
            console.error(error);
            // 6. Error Server / Koneksi
            alertBox.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Terjadi kesalahan koneksi ke server.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                  </div>`;
            btn.disabled = false;
            btn.innerText = originalBtnText;
        }
    });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        // Check if user is logged in via API
        const token = localStorage.getItem('authToken');
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        
        if (!token && !{{ Auth::check() ? 'true' : 'false' }}) {
            // Not authenticated, redirect to login
            window.location.href = '/login';
        }
    </script>

    <style>
        body {
            background-color: #f4f7fb;
        }

        .sidebar {
            width: 250px;
            background: #23c984;
            color: #fff;
            transition: all 0.3s ease;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                height: 100vh;
                z-index: 1050;
            }
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-light bg-white shadow-sm px-3">
    <button class="btn btn-outline-secondary" id="toggleSidebar">☰</button>
    <span class="ms-3 fw-bold">Dashboard</span>
</nav>

<div class="d-flex position-relative">

    <!-- SIDEBAR -->
    <div id="sidebar" class="sidebar p-4 vh-100">

        <ul class="nav flex-column gap-2">
            <li><a class="nav-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg> Profile</a></li>
            <li><a class="nav-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg> Services</a></li>
            <li class="nav-item">
                <button id="logoutBtn" type="button" class="nav-link btn btn-link text-white p-0">
                    🚪 Logout
                </button>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 p-4">

        <!-- PROFILE CARD -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex gap-4 align-items-center">
                <img src="https://via.placeholder.com/150" class="profile-img">
                <div>
                    <h4 class="mb-1" id="profileName">{{ Auth::user()?->name ?? '' }}</h4>
                    <p class="mb-1 text-muted" id="profileEmail">{{ Auth::user()?->email ?? '' }}</p>
                    <small class="text-muted" id="profileJoined">Bergabung: {{ Auth::user()?->created_at?->format('d M Y') ?? '' }}</small>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#editProfile">
                    Edit Profile
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#changePassword">
                    Ganti Password
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="editProfile">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="profileAlertContainer"></div>
                        <form id="editProfileForm">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" id="editName" value="{{ Auth::user()?->name ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" value="{{ Auth::user()?->email ?? '' }}">
                            </div>
                            <button type="submit" id="editProfileBtn" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="changePassword">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="passwordAlertContainer"></div>
                        <form id="changePasswordForm">
                            <div class="mb-3">
                                <label>Password Lama</label>
                                <input type="password" class="form-control" id="oldPassword">
                            </div>
                            <div class="mb-3">
                                <label>Password Baru</label>
                                <input type="password" class="form-control" id="newPassword">
                            </div>
                            <div class="mb-3">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="newPasswordConfirm">
                            </div>
                            <button type="submit" id="changePasswordBtn" class="btn btn-warning">Ganti Password</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    // Fetch user data from API
    async function fetchUserData() {
        const token = localStorage.getItem('authToken');
        
        try {
            const response = await fetch('/api/auth/user', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            });

            if (response.ok) {
                const user = await response.json();
                
                // Display profile data
                document.getElementById('profileName').textContent = user.name || 'null';
                document.getElementById('profileEmail').textContent = user.email || 'null';
                document.getElementById('editName').value = user.name || '';
                document.getElementById('editEmail').value = user.email || '';
                
                if (user.created_at) {
                    const joinedDate = new Date(user.created_at);
                    document.getElementById('profileJoined').textContent = 'Bergabung: ' + joinedDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                } else {
                    document.getElementById('profileJoined').textContent = 'Bergabung: null';
                }
                
                // Update localStorage
                localStorage.setItem('user', JSON.stringify(user));
            } else {
                console.error('Failed to fetch user data');
                // Fallback to localStorage
                const user = JSON.parse(localStorage.getItem('user') || '{}');
                if (user.name) {
                    document.getElementById('profileName').textContent = user.name;
                    document.getElementById('profileEmail').textContent = user.email;
                    document.getElementById('editName').value = user.name;
                    document.getElementById('editEmail').value = user.email;
                    const joinedDate = new Date(user.created_at);
                    document.getElementById('profileJoined').textContent = 'Bergabung: ' + joinedDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                }
            }
        } catch (error) {
            console.error('Error fetching user data:', error);
            // Fallback to localStorage
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.name) {
                document.getElementById('profileName').textContent = user.name;
                document.getElementById('profileEmail').textContent = user.email;
                document.getElementById('editName').value = user.name;
                document.getElementById('editEmail').value = user.email;
                const joinedDate = new Date(user.created_at);
                document.getElementById('profileJoined').textContent = 'Bergabung: ' + joinedDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            }
        }
    }

    // Fetch user data on page load
    document.addEventListener('DOMContentLoaded', fetchUserData);

    // Logout functionality
    document.getElementById('logoutBtn').addEventListener('click', async () => {
        const token = localStorage.getItem('authToken');

        try {
            const response = await fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            });

            // Clear localStorage
            localStorage.removeItem('authToken');
            localStorage.removeItem('user');

            // Redirect to login
            window.location.href = '/login';
        } catch (error) {
            console.error('Logout error:', error);
            // Force clear and redirect anyway
            localStorage.removeItem('authToken');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
    });

    // Edit Profile functionality
    document.getElementById('editProfileForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const token = localStorage.getItem('authToken');
        const name = document.getElementById('editName').value;
        const email = document.getElementById('editEmail').value;
        const btn = document.getElementById('editProfileBtn');
        const alertContainer = document.getElementById('profileAlertContainer');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

        try {
            const response = await fetch('/api/auth/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                }),
            });

            const data = await response.json();

            if (response.ok) {
                alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Update localStorage
                localStorage.setItem('user', JSON.stringify(data.user));

                // Update profile display
                document.getElementById('profileName').textContent = data.user.name;
                document.getElementById('profileEmail').textContent = data.user.email;
            } else {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${data.message || 'Failed to update profile'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }

            btn.disabled = false;
            btn.innerHTML = 'Simpan';
        } catch (error) {
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            btn.disabled = false;
            btn.innerHTML = 'Simpan';
        }
    });

    // Change Password functionality
    document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const token = localStorage.getItem('authToken');
        const oldPassword = document.getElementById('oldPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const newPasswordConfirm = document.getElementById('newPasswordConfirm').value;
        const btn = document.getElementById('changePasswordBtn');
        const alertContainer = document.getElementById('passwordAlertContainer');

        // Validate passwords match
        if (newPassword !== newPasswordConfirm) {
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> New passwords do not match
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

        try {
            const response = await fetch('/api/auth/password', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    old_password: oldPassword,
                    new_password: newPassword,
                    new_password_confirmation: newPasswordConfirm,
                }),
            });

            const data = await response.json();

            if (response.ok) {
                alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Clear form
                document.getElementById('changePasswordForm').reset();
            } else {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${data.message || 'Failed to change password'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }

            btn.disabled = false;
            btn.innerHTML = 'Ganti Password';
        } catch (error) {
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            btn.disabled = false;
            btn.innerHTML = 'Ganti Password';
        }
    });

    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

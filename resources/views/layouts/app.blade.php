<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        const token = localStorage.getItem('authToken');
        // Jika tidak ada token JS dan tidak login Laravel session
        if (!token && !{{ Auth::check() ? 'true' : 'false' }}) {
            window.location.href = '/login';
        }
    </script>

    <style>
        body { background-color: #f4f7fb; }
        .sidebar { width: 250px; background: #23c984; color: #fff; transition: all 0.3s ease; }
        .sidebar a { color: #fff; text-decoration: none; }
        .sidebar a:hover { background: rgba(255,255,255,0.15); border-radius: 8px; }
        .sidebar.collapsed { margin-left: -250px; }
        @media (max-width: 768px) {
            .sidebar { position: absolute; height: 100vh; z-index: 1050; }
        }
        .profile-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm px-3">
    <button class="btn btn-outline-secondary" id="toggleSidebar">☰</button>
    <span class="ms-3 fw-bold">Dashboard</span>
</nav>

<div class="d-flex position-relative">
    
    @include('partials.sidebar')

    <div class="flex-grow-1 p-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global Script (Sidebar Toggle & Logout)
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if(toggleBtn){
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }

    // Logout Logic (Dipindah ke global agar sidebar bisa jalan dimanapun)
    const logoutBtn = document.getElementById('logoutBtn');
    if(logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            const token = localStorage.getItem('authToken');
            try {
                await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }
                });
            } catch (error) { console.error('Logout err', error); }
            
            localStorage.removeItem('authToken');
            localStorage.removeItem('user');
            window.location.href = '/login';
        });
    }
</script>

@stack('scripts')

</body>
</html>
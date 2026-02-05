<div id="sidebar" class="sidebar p-4 vh-100">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-secondary">
        <div class="image">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-circle text-white" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </div>
        <div class="info ms-2">
            <a href="#" class="d-block text-white text-decoration-none" id="sidebarUserName">Loading...</a>
        </div>
    </div>

    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <a class="nav-link" id="link-dashboard" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                     <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.389.389 0 0 0-.527-.02L9.647 7.6a.5.5 0 0 1-.708-.708l2.585-2.585a.389.389 0 0 0-.02-.527l-.02-.02L11.4 3.7a.5.5 0 0 1-.7 0L8 6.4 5.3 3.7a.5.5 0 0 1-.7 0L3.522 2.622a.389.389 0 0 0-.527.02l-.02.02a.389.389 0 0 0 .02.527l2.585 2.585a.5.5 0 0 1-.708.708L2.322 3.846a.389.389 0 0 0-.02.527l.02.02L3.7 5.7a.5.5 0 0 1 0 .7L1 9.1a.5.5 0 0 1 0-.7L3.7 5.7a.5.5 0 0 1 0-.7L1 2.3a.5.5 0 0 1 0-.7l2.7-2.7a.5.5 0 0 1 .7 0l2.7 2.7a.5.5 0 0 1 .7 0l2.7-2.7a.5.5 0 0 1 .7 0L16 2.3a.5.5 0 0 1 0 .7l-2.7 2.7a.5.5 0 0 1 0 .7L16 9.1a.5.5 0 0 1 0 .7l-2.7 2.7a.5.5 0 0 1 0-.7L16 9.1z"/>
                </svg>
                <span class="ms-2">Dashboard</span>
            </a>
        </li>

        <li class="nav-item" id="menu-users" style="display: none;">
            <a class="nav-link" href="{{ route('admin.users') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.6-.69 1.146-1.264 3.24-1.276"/>
                </svg> 
                <span class="ms-2">Users</span>
            </a>
        </li>

        <li class="nav-item" id="menu-services-admin" style="display: none;">
            <a class="nav-link" href="{{ route('admin.services') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                </svg> 
                <span class="ms-2">Services</span>
            </a>
        </li>

        <li class="nav-item" id="menu-services-user" style="display: none;">
            <a class="nav-link" href="{{ route('user.services') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                </svg> 
                <span class="ms-2">Services</span>
            </a>
        </li>

        <li class="nav-item">
            <button id="logoutBtn" type="button" class="nav-link btn btn-link text-white p-0 text-start w-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 0h-8A1.5 1.5 0 0 0 0 1.5v9A1.5 1.5 0 0 0 1.5 12h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
                <span class="ms-2">Logout</span>
            </button>
        </li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. SETUP DATA USER ---
    const userStr = localStorage.getItem('userData');
    const token = localStorage.getItem('authToken');

    if (!userStr || !token) {
        window.location.href = '/login'; 
        return;
    }

    const user = JSON.parse(userStr);

    // --- 2. TAMPILKAN INFO USER & MENU SESUAI ROLE ---
    const userNameEl = document.getElementById('sidebarUserName');
    if(userNameEl) userNameEl.innerText = user.name || 'User';

    const dashLink = document.getElementById('link-dashboard');
    if (user.role === 'admin') {
        dashLink.href = '/admin/dashboard';
        document.getElementById('menu-users').style.display = 'block';
        document.getElementById('menu-services-admin').style.display = 'block';
    } else {
        dashLink.href = '/dashboard';
        document.getElementById('menu-services-user').style.display = 'block';
    }

    // --- 3. LOGIKA LOGOUT (CLEAN) ---
    const logoutBtn = document.getElementById('logoutBtn');
    
    // Hapus listener lama jika ada (dengan cara clone element)
    const newLogoutBtn = logoutBtn.cloneNode(true);
    logoutBtn.parentNode.replaceChild(newLogoutBtn, logoutBtn);

    // Pasang listener baru yang bersih
    newLogoutBtn.addEventListener('click', async (e) => {
        e.preventDefault(); 
        e.stopPropagation();

        const isConfirmed = confirm('Apakah Anda yakin ingin keluar?');

        if (isConfirmed) {
            // JIKA YES: Logout & Redirect
            try {
                await fetch('/api/auth/logout', { 
                    method: 'POST', 
                    headers: { 
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    } 
                });
            } catch (err) {
                console.log('Error API Logout (diabaikan)', err);
            }

            localStorage.clear();
            window.location.href = '/login';
        } 
        // JIKA NO (CANCEL): Kode berhenti di sini. Tidak ada redirect.
    });

});
</script>
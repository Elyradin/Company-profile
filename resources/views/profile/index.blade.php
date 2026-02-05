@extends('layouts.app')

@section('title', 'Dashboard & Profile')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard & Profile</h1>
        </div>
    </div>

    <div class="row mb-4">
        
        <div class="col-lg-6 col-6" id="card-total-users" style="display: none;">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="countUsers">0</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users') }}" class="small-box-footer">
                    Manage Users <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-6 col-6" id="card-total-services" style="display: none;">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="countServices">0</h3>
                    <p>Available Services</p>
                </div>
                <div class="icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <a href="{{ route('admin.services') }}" class="small-box-footer">
                    Manage Services <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex gap-4 align-items-center">
            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 40px;">
                <i class="fas fa-user text-white"></i>
            </div>
            
            <div>
                <h4 class="mb-1 fw-bold" id="profileName">Loading...</h4>
                <p class="mb-1 text-muted" id="profileEmail">...</p>
                <small class="text-muted" id="profileJoined">...</small>
                <br>
                <span id="profileRoleBadge" class="badge bg-primary mt-1">...</span>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-edit-profile" data-bs-toggle="pill" href="#content-edit-profile" role="tab">Edit Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-change-password" data-bs-toggle="pill" href="#content-change-password" role="tab">Change Password</a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                
                <div class="tab-pane fade show active" id="content-edit-profile" role="tabpanel">
                    <div id="profileAlert"></div>
                    <form id="editProfileForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <button type="submit" id="btnSaveProfile" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="content-change-password" role="tabpanel">
                    <div id="passwordAlert"></div>
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input type="password" class="form-control" id="oldPassword" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="newPasswordConfirm" required>
                        </div>
                        <button type="submit" id="btnChangePassword" class="btn btn-warning">Update Password</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- 1. SETUP HEADER TOKEN ---
    function getAuthHeaders() {
        const token = localStorage.getItem('authToken');
        if (!token) {
            window.location.href = '/login';
            return null;
        }
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        };
    }

    // --- 2. FETCH DATA (USER & STATS) ---
    async function initPage() {
        const headers = getAuthHeaders();
        if (!headers) return;

        // A. Fetch User Profile
        try {
            const res = await fetch('/api/auth/user', { headers: headers });
            
            if (res.status === 401) {
                localStorage.removeItem('authToken');
                window.location.href = '/login';
                return;
            }

            const user = await res.json();

            // Populate Profile Data
            document.getElementById('profileName').textContent = user.name;
            document.getElementById('profileEmail').textContent = user.email;
            document.getElementById('profileRoleBadge').textContent = user.role.toUpperCase();
            
            const date = new Date(user.created_at);
            document.getElementById('profileJoined').textContent = 'Joined: ' + date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });

            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;

            // --- LOGIKA STATISTIK (HANYA ADMIN) ---
            if (user.role === 'admin') {
                // 1. Tampilkan Kartu Users
                const cardUsers = document.getElementById('card-total-users');
                cardUsers.style.display = 'block';
                
                // 2. Tampilkan Kartu Services
                const cardServices = document.getElementById('card-total-services');
                cardServices.style.display = 'block';

                // 3. Ambil Data Statistik
                fetchStats('/api/admin/users', 'countUsers');
                fetchStats('/api/admin/services', 'countServices');
            } 
            // Jika User Biasa, kedua kartu tetap hidden (style="display:none")

            // Sync LocalStorage
            localStorage.setItem('userData', JSON.stringify(user));

        } catch (error) {
            console.error('Error fetching profile:', error);
        }
    }

    // Helper to fetch count
    async function fetchStats(url, elementId) {
        try {
            const headers = getAuthHeaders();
            const res = await fetch(url, { headers });
            if (res.ok) {
                const data = await res.json();
                const count = Array.isArray(data) ? data.length : (data.data ? data.data.length : 0);
                document.getElementById(elementId).textContent = count;
            }
        } catch (e) {
            console.error('Failed to fetch stats for ' + elementId, e);
        }
    }

    // --- 3. HANDLE EDIT PROFILE ---
    document.getElementById('editProfileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const headers = getAuthHeaders();
        const btn = document.getElementById('btnSaveProfile');
        
        btn.disabled = true;
        btn.innerText = 'Saving...';

        const payload = {
            name: document.getElementById('editName').value,
            email: document.getElementById('editEmail').value
        };

        try {
            const res = await fetch('/api/auth/profile', {
                method: 'PUT',
                headers: headers,
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok) {
                Swal.fire('Success', 'Profile updated successfully!', 'success');
                initPage(); // Refresh data
            } else {
                Swal.fire('Error', data.message || 'Update failed', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Server error', 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Save Changes';
        }
    });

    // --- 4. HANDLE CHANGE PASSWORD ---
    document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const headers = getAuthHeaders();
        const btn = document.getElementById('btnChangePassword');

        const oldPass = document.getElementById('oldPassword').value;
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('newPasswordConfirm').value;

        btn.disabled = true;
        btn.innerText = 'Processing...';

        try {
            const res = await fetch('/api/auth/password', {
                method: 'PUT',
                headers: headers,
                body: JSON.stringify({
                    old_password: oldPass,
                    new_password: newPass,
                    new_password_confirmation: confirmPass
                })
            });

            const data = await res.json();

            if (res.ok) {
                Swal.fire('Success', 'Password changed successfully!', 'success');
                document.getElementById('changePasswordForm').reset();
            } else {
                Swal.fire('Error', data.message || 'Failed to change password', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Server error', 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Update Password';
        }
    });

    // Run on load
    document.addEventListener('DOMContentLoaded', initPage);
</script>
@endpush
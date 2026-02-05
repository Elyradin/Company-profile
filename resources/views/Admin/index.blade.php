@extends('layouts.app')

@section('title', 'Manajemen Users')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Users Management (API Token)</h1>
        </div>
        <div class="col-sm-6 text-end">
            <button id="refreshBtn" class="btn btn-secondary btn-sm">
                <i class="fas fa-sync"></i> Refresh
            </button>
            <button id="createBtn" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="fas fa-plus"></i> Create User
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengguna</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th style="width: 150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersBody">
                        <tr><td colspan="5" class="text-center">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalAlert"></div>
                <form id="userForm">
                    <input type="hidden" id="userId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="userName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="userEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select id="userRole" class="form-select">
                            <option value="member">member</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" id="userPassword" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" id="userPasswordConfirm" class="form-control">
                    </div>
                    <button type="submit" id="saveUserBtn" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const alertContainer = document.getElementById('alertContainer');
    const usersBody = document.getElementById('usersBody');
    const refreshBtn = document.getElementById('refreshBtn');

    const modalElement = document.getElementById('userModal');
    const modal = new bootstrap.Modal(modalElement);
    const modalTitle = document.getElementById('modalTitle');
    const modalAlert = document.getElementById('modalAlert');

    const userForm = document.getElementById('userForm');
    const userId = document.getElementById('userId');
    const userName = document.getElementById('userName');
    const userEmail = document.getElementById('userEmail');
    const userRole = document.getElementById('userRole');
    const userPassword = document.getElementById('userPassword');
    const userPasswordConfirm = document.getElementById('userPasswordConfirm');

    // --- 1. FUNGSI HEADER AUTH (Menggunakan Token) ---
    function getAuthHeaders() {
        const token = localStorage.getItem('authToken');
        
        // Jika token hilang, paksa logout/login ulang
        if (!token) {
            window.location.href = '/login';
            return null;
        }

        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token // <--- INI KUNCINYA
        };
    }

    // --- 2. FETCH DATA (API ONLY) ---
    async function fetchUsers() {
        usersBody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';
        
        const headers = getAuthHeaders();
        if (!headers) return; // Stop jika tidak ada token

        try {
            // Tembak langsung ke API (sesuaikan jika prefix berbeda)
            const res = await fetch('/api/admin/users', { 
                method: 'GET',
                headers: headers
            }); 

            // Handle Token Expired / Invalid
            if (res.status === 401) {
                alert('Sesi Anda telah berakhir. Silakan login kembali.');
                localStorage.removeItem('authToken'); // Hapus token basi
                window.location.href = '/login';
                return;
            }

            if (res.ok) {
                const data = await res.json();
                renderUsers(data); // Sesuaikan jika data dibungkus 'data.data'
            } else {
                throw new Error('Gagal mengambil data dari server');
            }

        } catch (e) { 
            console.error(e);
            usersBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${e.message}</td></tr>`;
        }
    }

    // --- 3. RENDER TABEL (Dengan No Urut Index) ---
    function renderUsers(users) {
        // Cek struktur data (kadang Laravel API Resource membungkus dalam .data)
        const list = Array.isArray(users) ? users : (users.data || []);

        if (list.length === 0) {
            usersBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada user ditemukan.</td></tr>';
            return;
        }
        
        usersBody.innerHTML = '';

        list.forEach((u, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${u.name || '-'}</td>
                <td>${u.email || '-'}</td>
                <td>
                    <span class="badge ${u.role === 'admin' ? 'bg-danger' : 'bg-primary'}">${u.role}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-info text-white editBtn" 
                        data-id="${u.id}" 
                        data-name="${u.name}" 
                        data-email="${u.email}" 
                        data-role="${u.role}">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${u.id}">
                        Delete
                    </button>
                </td>
            `;
            usersBody.appendChild(tr);
        });

        document.querySelectorAll('.editBtn').forEach(b => b.addEventListener('click', onEdit));
        document.querySelectorAll('.deleteBtn').forEach(b => b.addEventListener('click', onDelete));
    }

    function showAlert(message, type='success') {
        alertContainer.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
    }

    // --- 4. FORM HANDLER (CREATE/UPDATE) ---
    document.getElementById('createBtn').addEventListener('click', () => {
        modalTitle.textContent = 'Create User';
        modalAlert.innerHTML = '';
        userForm.reset();
        userId.value = '';
    });

    userForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        modalAlert.innerHTML = '';
        
        const headers = getAuthHeaders();
        if(!headers) return;

        const id = userId.value;
        const payload = {
            name: userName.value,
            email: userEmail.value,
            role: userRole.value,
        };
        
        if (userPassword.value) {
            payload.password = userPassword.value;
            payload.password_confirmation = userPasswordConfirm.value;
        }

        const url = id ? `/api/admin/users/${id}` : '/api/admin/users';
        const method = id ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method, 
                headers: headers, 
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok) {
                modal.hide();
                showAlert(data.message || 'Data berhasil disimpan');
                fetchUsers();
            } else {
                // Tampilkan error validasi
                modalAlert.innerHTML = `<div class="alert alert-danger">${data.message || 'Gagal menyimpan data'}</div>`;
            }
        } catch (err) {
            modalAlert.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
        }
    });

    // --- 5. EDIT & DELETE HANDLERS ---
    function onEdit(e) {
        const btn = e.currentTarget;
        modalTitle.textContent = 'Edit User';
        modalAlert.innerHTML = '';
        
        userId.value = btn.dataset.id;
        userName.value = btn.dataset.name;
        userEmail.value = btn.dataset.email;
        userRole.value = btn.dataset.role;
        userPassword.value = '';
        userPasswordConfirm.value = '';
        
        modal.show();
    }

    async function onDelete(e) {
        if (!confirm('Yakin ingin menghapus user ini?')) return;
        
        const id = e.currentTarget.dataset.id;
        const headers = getAuthHeaders();
        if(!headers) return;

        try {
            const res = await fetch('/api/admin/users/' + id, {
                method: 'DELETE', 
                headers: headers
            });
            
            const data = await res.json();
            
            if (res.ok) {
                showAlert(data.message || 'User deleted');
                fetchUsers();
            } else {
                showAlert(data.message || 'Failed to delete', 'danger');
            }
        } catch (err) {
            showAlert(err.message, 'danger');
        }
    }

    refreshBtn.addEventListener('click', fetchUsers);
    
    // Initial Load
    fetchUsers();
</script>
@endpush
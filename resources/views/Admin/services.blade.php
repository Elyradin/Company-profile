@extends('layouts.app')

@section('title', 'Admin - Kelola Services')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Services Management</h1>
        </div>
        <div class="col-sm-6 text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <button class="btn btn-primary btn-sm" onclick="openModal('create')">
                <i class="fas fa-plus"></i> Tambah Service
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Services</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Nama Service</th>
                            <th>Deskripsi</th>
                            <th style="width: 150px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="4" class="text-center py-3">Sedang memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Form Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalAlert"></div>
                <form id="serviceForm">
                    <input type="hidden" id="serviceId">
                    <div class="mb-3">
                        <label class="form-label">Nama Service</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="saveBtn">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- SETUP AWAL ---
    // Sesuaikan URL ini dengan route di api.php
    const API_URL = '/api/admin/services'; 
    
    const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    const modalAlert = document.getElementById('modalAlert');
    let isEditMode = false;

    // --- 1. FUNGSI HEADER AUTH (Token) ---
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

    // --- 2. FUNGSI BACA DATA (READ) ---
    async function fetchServices() {
        const tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-3">Sedang memuat data...</td></tr>';
        
        const headers = getAuthHeaders();
        if (!headers) return;

        try {
            const res = await fetch(API_URL, { headers: headers });
            
            if (res.status === 401) {
                alert('Sesi habis. Silakan login kembali.');
                window.location.href = '/login';
                return;
            }

            const data = await res.json();
            
            // Cek jika data dibungkus dalam .data (standar API Resource Laravel)
            const list = Array.isArray(data) ? data : (data.data || []);
            let rows = '';

            if(list.length === 0) {
                rows = `<tr><td colspan="4" class="text-center py-3">Belum ada data service.</td></tr>`;
            } else {
                list.forEach((item, index) => {
                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="fw-bold">${escapeHtml(item.name)}</td>
                            <td>${escapeHtml(item.description)}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white me-1" 
                                    onclick="editMode(${item.id}, '${escapeHtml(item.name)}', '${escapeHtml(item.description)}')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteData(${item.id})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            tableBody.innerHTML = rows;

        } catch (err) {
            console.error(err);
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Gagal mengambil data: ${err.message}</td></tr>`;
        }
    }

    // --- 3. FUNGSI SIMPAN (CREATE/UPDATE) ---
    document.getElementById('serviceForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        modalAlert.innerHTML = '';

        const headers = getAuthHeaders();
        if (!headers) return;
        
        const id = document.getElementById('serviceId').value;
        const payload = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value
        };

        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        const url = isEditMode ? `${API_URL}/${id}` : API_URL;
        const method = isEditMode ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: headers,
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok) {
                Swal.fire('Berhasil!', 'Data telah disimpan.', 'success');
                serviceModal.hide();
                fetchServices();
            } else {
                modalAlert.innerHTML = `<div class="alert alert-danger">${data.message || 'Gagal menyimpan data'}</div>`;
            }
        } catch (err) {
            modalAlert.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
        } finally {
            btn.disabled = false;
            btn.innerText = 'Simpan';
        }
    });

    // --- 4. FUNGSI HAPUS (DELETE) ---
    window.deleteData = (id) => {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data tidak bisa kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const headers = getAuthHeaders();
                if (!headers) return;

                try {
                    const res = await fetch(`${API_URL}/${id}`, {
                        method: 'DELETE',
                        headers: headers
                    });
                    
                    if (res.ok) {
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                        fetchServices();
                    } else {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error!', err.message, 'error');
                }
            }
        });
    }

    // --- HELPERS ---
    function openModal(mode) {
        isEditMode = false;
        document.getElementById('modalTitle').innerText = 'Tambah Service';
        modalAlert.innerHTML = '';
        document.getElementById('serviceForm').reset();
        document.getElementById('serviceId').value = '';
        serviceModal.show();
    }

    window.editMode = (id, name, desc) => {
        isEditMode = true;
        document.getElementById('modalTitle').innerText = 'Edit Service';
        modalAlert.innerHTML = '';
        document.getElementById('serviceId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('description').value = desc;
        serviceModal.show();
    }

    function escapeHtml(text) {
        return text ? text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;") : "";
    }

    // Load data saat halaman dibuka
    fetchServices();
</script>
@endpush
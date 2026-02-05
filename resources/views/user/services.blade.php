@extends('layouts.app')

@section('title', 'Layanan Kami')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Layanan</h2>
        <button class="btn btn-outline-primary btn-sm" onclick="fetchServices()">
            <i class="fas fa-sync"></i> Refresh
        </button>
    </div>

    <div class="row" id="servicesGrid">
        <div class="col-12 text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat layanan...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // URL API (Sesuai routes/api.php)
    const API_URL = '/api/services'; 

    // 1. Setup Header Auth (Ambil Token)
    function getAuthHeaders() {
        const token = localStorage.getItem('authToken');
        
        if (!token) {
            // Jika tidak ada token, paksa login
            window.location.href = '/login'; 
            return null;
        }

        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        };
    }

    // 2. Fungsi Fetch Data
    async function fetchServices() {
        const grid = document.getElementById('servicesGrid');
        
        // Tampilkan Loading state saat refresh
        grid.innerHTML = `
            <div class="col-12 text-center p-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Memuat layanan...</p>
            </div>
        `;

        const headers = getAuthHeaders();
        if (!headers) return;

        try {
            const res = await fetch(API_URL, {
                method: 'GET',
                headers: headers
            });

            // Handle jika token expired
            if (res.status === 401) {
                alert('Sesi Anda telah berakhir. Silakan login kembali.');
                localStorage.removeItem('authToken');
                window.location.href = '/login';
                return;
            }

            const data = await res.json();
            
            // Cek struktur data (kadang dibungkus .data)
            const list = Array.isArray(data) ? data : (data.data || []);
            let content = '';

            if (list.length === 0) {
                content = `
                    <div class="col-12 text-center">
                        <div class="alert alert-info">Belum ada layanan yang tersedia saat ini.</div>
                    </div>`;
            } else {
                list.forEach(item => {
                    content += `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-grid-fill" viewBox="0 0 16 16">
                                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                                            </svg>
                                        </div>
                                        <h5 class="card-title mb-0 ms-3 fw-bold">${escapeHtml(item.name)}</h5>
                                    </div>
                                    <p class="card-text text-secondary">${escapeHtml(item.description)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            grid.innerHTML = content;

        } catch (err) {
            console.error(err);
            grid.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-danger">Gagal memuat data: ${err.message}</div>
                </div>`;
        }
    }

    function escapeHtml(text) {
        return text ? text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;") : "";
    }

    document.addEventListener('DOMContentLoaded', fetchServices);
</script>
@endpush
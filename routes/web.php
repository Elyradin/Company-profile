<?php

use Illuminate\Support\Facades\Route;

// Halaman Login (Hanya Tampilan)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');

// Halaman Dashboard & Admin (Hanya Tampilan)
// Kita tidak pakai middleware 'auth' bawaan Laravel session, 
// karena pengecekan login akan dilakukan oleh JavaScript di browser.
Route::get('/dashboard', function () { return view('profile.index'); })->name('dashboard');
Route::get('/services', function () { return view('user.services'); })->name('user.services');

Route::get('/admin/dashboard', function () { return view('profile.index'); })->name('admin.dashboard');
Route::get('/admin/users', function () { return view('admin.index'); })->name('admin.users');
Route::get('/admin/services', function () { return view('admin.services'); })->name('admin.services');

// Default
Route::get('/', function () { return view('welcome'); });
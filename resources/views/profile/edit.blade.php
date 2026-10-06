@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="page-header">
    <div>
        <h1>Profil Pengguna</h1>
        <p class="text-secondary mb-0">Kelola informasi akun dan pengaturan keamanan.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center p-4">
                <div class="user-avatar mx-auto mb-3 shadow" style="width: 100px; height: 100px; font-size: 36px;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2 text-capitalize">
                        <i class="bi bi-person-badge me-1"></i> Role: {{ $user->role }}
                    </span>
                    @if($user->pegawai)
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i> Terhubung Pegawai
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Profile Info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Informasi Akun</h5>
                <p class="text-muted small mb-0">Perbarui nama profil dan alamat email akun Anda.</p>
            </div>
            <div class="card-body p-4">
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" class="mt-2">
                    @csrf
                    @method('patch')

                    <div class="mb-4">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-3">
                                <p class="text-warning small mb-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Alamat email Anda belum diverifikasi.
                                </p>
                                <button form="send-verification" class="btn btn-sm btn-outline-warning">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small fw-medium" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                                <i class="bi bi-check2-circle me-1"></i> Tersimpan.
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Ubah Kata Sandi</h5>
                <p class="text-muted small mb-0">Pastikan akun Anda menggunakan kata sandi panjang dan acak untuk tetap aman.</p>
            </div>
            <div class="card-body p-4">
                <form method="post" action="{{ route('password.update') }}" class="mt-2">
                    @csrf
                    @method('put')

                    <div class="mb-4">
                        <label for="update_password_current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
                        @error('current_password', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="update_password_password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
                        @error('password', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="update_password_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Kata Sandi</button>
                        
                        @if (session('status') === 'password-updated')
                            <span class="text-success small fw-medium" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                                <i class="bi bi-check2-circle me-1"></i> Tersimpan.
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

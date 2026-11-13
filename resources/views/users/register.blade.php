@extends('layouts.app')

@section('title', 'Daftarkan akun')

@section('content')
    <div class="page-head">
        <div><div class="eyebrow">MANAJEMEN AKUN</div><h1>Daftarkan akun</h1><p class="sub">Buat akun admin, gudang, atau kasir. Hanya admin yang dapat mendaftarkan pengguna.</p></div>
    </div>
    <section class="panel">
        <form method="POST" action="{{ route('users.register.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field"><label for="name">NAMA LENGKAP *</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" placeholder="Nama pengguna"></div>
                <div class="field"><label for="email">EMAIL *</label><input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" placeholder="nama@perusahaan.com"></div>
                <div class="field"><label for="role">PERAN *</label><select id="role" name="role" required><option value="gudang" @selected(old('role', 'gudang') === 'gudang')>Gudang</option><option value="kasir" @selected(old('role') === 'kasir')>Kasir</option><option value="admin" @selected(old('role') === 'admin')>Admin</option></select><span class="help">Admin dapat mengelola produk dan membuat akun pengguna.</span></div>
                <div class="field"><label for="password">KATA SANDI *</label><input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter"></div>
                <div class="field"><label for="password_confirmation">ULANGI KATA SANDI *</label><input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Ulangi kata sandi"></div>
            </div>
            <div class="form-actions"><a class="btn btn-light" href="{{ route('dashboard') }}">Batal</a><button class="btn" type="submit">Daftarkan akun</button></div>
        </form>
    </section>
@endsection

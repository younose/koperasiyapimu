<!doctype html><html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Masuk — {{ nama_koperasi() }}</title><link rel="icon" href="{{ asset('logo.svg') }}">
<link rel="manifest" href="{{ route('manifest') }}"><meta name="theme-color" content="#0e6b56">
<link rel="stylesheet" href="{{ asset('css/app.css') }}"></head><body>
<div class="login-wrap"><div class="login-card">
  <img src="{{ asset('logo.svg') }}" alt="Logo Koperasi">
  <div class="login-title">{{ nama_koperasi() }}</div>
  <div class="login-sub">Sistem Simpanan &amp; Pinjaman Anggota</div>
  <form method="post" action="{{ route('login.post') }}" class="login-form">@csrf
    <div class="field"><label>ID Pengguna</label>
      <input type="text" name="id" value="{{ old('id') }}" placeholder="admin / AGT-001" autofocus required>
      <span class="hint">Pengurus: <b>admin</b> · Anggota: No. Anggota</span></div>
    <div class="field"><label>PIN / Kata sandi</label>
      <input type="password" name="pin" placeholder="••••" required></div>
    @if(session('err'))<div class="alert alert-err">{{ session('err') }}</div>@endif
    @if($errors->any())<div class="alert alert-err">{{ $errors->first() }}</div>@endif
    <button class="btn btn-primary btn-block" type="submit">Masuk</button>
  </form>
  <div class="login-hint">Akun demo — Pengurus: <b>admin / admin123</b><br>Anggota: <b>AGT-001 / 1001</b></div>
</div></div></body></html>

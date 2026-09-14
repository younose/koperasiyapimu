@extends('layouts.app')
@section('content')
<div class="two">
  <section class="panel">
    <div class="phead"><h2>Pengaturan Umum</h2></div>
    <form method="post" action="{{ route('pengaturan.umum') }}">@csrf
      <div class="field"><label>Nama Koperasi</label><input type="text" name="nama_koperasi" value="{{ nama_koperasi() }}" required></div>
      <div class="grid2">
        <div class="field"><label>Iuran wajib / bulan (Rp)</label><input type="number" name="iuran_nominal" value="{{ (int)setting('iuran_nominal',25000) }}"></div>
        <div class="field"><label>Faktor plafon (× sukarela)</label><input type="number" step="0.1" name="faktor_plafon" value="{{ setting('faktor_plafon',2) }}"></div>
      </div>
      <div class="grid2">
        <div class="field"><label>Jatuh tempo — tgl awal</label><input type="number" min="1" max="28" name="jatuh_tempo_awal" value="{{ setting('jatuh_tempo_awal',5) }}"></div>
        <div class="field"><label>Jatuh tempo — tgl akhir</label><input type="number" min="1" max="28" name="jatuh_tempo_akhir" value="{{ setting('jatuh_tempo_akhir',10) }}"></div>
      </div>
      <div class="field"><label>Jasa pinjaman (%/bulan)</label><input type="number" step="0.1" name="jasa_pinjaman" value="{{ setting('jasa_pinjaman',1) }}"></div>
      <button class="btn btn-primary">Simpan Pengaturan</button>
    </form>
  </section>
  <section class="panel">
    <div class="phead"><h2>Ganti Password Pengurus</h2></div>
    <form method="post" action="{{ route('pengaturan.password') }}">@csrf
      <div class="field"><label>Password lama</label><input type="password" name="lama" required></div>
      <div class="field"><label>Password baru</label><input type="password" name="baru" required><span class="hint">Minimal 5 karakter.</span></div>
      <button class="btn btn-primary">{!! icon('key') !!} Perbarui Password</button>
    </form>
    <p class="note">{!! icon('info') !!} Untuk keamanan, ganti password default <b>admin123</b> setelah aplikasi live.</p>
  </section>
</div>
@endsection

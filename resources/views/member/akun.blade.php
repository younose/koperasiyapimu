@extends('layouts.app')
@section('content')
<div class="two">
  <section class="panel">
    <div class="phead"><h2>Data Keanggotaan</h2></div>
    <table style="font-family:inherit"><tbody>
      <tr><td style="color:var(--muted)">No. Anggota</td><td><b class="mono">{{ $me->no_anggota }}</b></td></tr>
      <tr><td style="color:var(--muted)">Nama</td><td>{{ $me->nama }}</td></tr>
      <tr><td style="color:var(--muted)">Telepon</td><td>{{ $me->telepon ?: '—' }}</td></tr>
      <tr><td style="color:var(--muted)">Alamat</td><td>{{ $me->alamat ?: '—' }}</td></tr>
      <tr><td style="color:var(--muted)">Bergabung</td><td>{{ tgl_id($me->tgl_gabung) }}</td></tr>
      <tr><td style="color:var(--muted)">Status</td><td><span class="pill p-ok">{{ $me->aktif?'Aktif':'Nonaktif' }}</span></td></tr>
    </tbody></table>
    <a class="btn btn-block" style="margin-top:12px" href="{{ route('cetak.kartu',$me) }}" target="_blank">{!! icon('card') !!} Cetak Kartu Anggota</a>
  </section>
  <section class="panel">
    <div class="phead"><h2>Ganti PIN</h2></div>
    <form method="post" action="{{ route('akun.pin') }}">@csrf
      <div class="field"><label>PIN lama</label><input type="password" name="lama" required></div>
      <div class="field"><label>PIN baru</label><input type="password" name="baru" required><span class="hint">Minimal 4 karakter.</span></div>
      <div class="field"><label>Ulangi PIN baru</label><input type="password" name="ulang" required></div>
      <button class="btn btn-primary">{!! icon('key') !!} Perbarui PIN</button>
    </form>
    <p class="note">{!! icon('info') !!} PIN dipakai untuk masuk ke portal bersama No. Anggota Anda.</p>
  </section>
</div>
@endsection

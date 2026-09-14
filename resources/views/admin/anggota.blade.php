@extends('layouts.app')
@section('content')
<div class="bar">
  <form class="search" method="get">{!! icon('search') !!}<input type="text" name="q" value="{{ $q }}" placeholder="Cari nama, nomor, telepon…"></form>
  <button class="btn btn-primary" onclick="openForm()">{!! icon('plus') !!} Tambah Anggota</button>
</div>
<section class="panel np">
<table><thead><tr><th>No. / Akun</th><th>Nama</th><th>Kontak</th><th class="r">Pokok</th><th class="r">Wajib</th><th class="r">Sukarela</th><th class="r">Total</th><th></th></tr></thead><tbody>
@forelse($rows as $m)
  <tr>
    <td><div class="cs mono">{{ $m->no_anggota }}</div><div class="pill p-warn" style="margin-top:3px">{!! icon('key') !!} PIN {{ $m->pin }}</div></td>
    <td><div class="cs">{{ $m->nama }}</div><div class="csub">Gabung {{ tgl_id($m->tgl_gabung) }}</div></td>
    <td><div>{{ $m->telepon ?: '—' }}</div><div class="csub">{{ $m->alamat }}</div></td>
    <td class="r mono">{{ rupiah($m->saldoJenis('Pokok')) }}</td>
    <td class="r mono">{{ rupiah($m->saldoJenis('Wajib')) }}</td>
    <td class="r mono">{{ rupiah($m->saldoJenis('Sukarela')) }}</td>
    <td class="r mono gold">{{ rupiah($m->totalSimpanan()) }}</td>
    <td class="r nowrap">
      <a class="ic" href="{{ route('cetak.kartu',$m) }}" target="_blank" title="Cetak kartu">{!! icon('card') !!}</a>
      <button class="ic" onclick='editRow(@json($m, JSON_HEX_APOS|JSON_HEX_QUOT))'>{!! icon('pencil') !!}</button>
      <form method="post" action="{{ route('anggota.destroy',$m) }}" style="display:inline" onsubmit="return confirm('Hapus anggota ini beserta seluruh datanya?')">@csrf
        <button class="ic d">{!! icon('trash') !!}</button></form>
    </td>
  </tr>
@empty<tr><td colspan="8" class="empty">Belum ada anggota.</td></tr>@endforelse
</tbody></table>
</section>
<p class="note">{!! icon('info') !!} Anggota masuk ke portal dengan <b>No. Anggota</b> sebagai ID dan <b>PIN</b> di atas.</p>

<div class="overlay" id="mForm" style="display:none"><div class="modal"><form method="post" id="fEl" action="{{ route('anggota.store') }}">@csrf
  <div class="mhead"><h3 id="mTitle">Tambah Anggota</h3><button type="button" class="ic" onclick="closeModal('mForm')">{!! icon('x') !!}</button></div>
  <div class="mbody">
    <div class="grid2">
      <div class="field"><label>No. Anggota (ID login)</label><input type="text" name="no" id="f_no" required></div>
      <div class="field"><label>PIN akun</label><input type="text" name="pin" id="f_pin"></div>
    </div>
    <div class="field"><label>Nama lengkap</label><input type="text" name="nama" id="f_nama" required></div>
    <div class="grid2">
      <div class="field"><label>Nomor telepon</label><input type="text" name="telepon" id="f_tel"></div>
      <div class="field"><label>Alamat</label><input type="text" name="alamat" id="f_al"></div>
    </div>
    <div class="field" id="f_pokokWrap"><label>Simpanan pokok (Rp)</label><input type="number" name="pokok" id="f_pokok" value="100000"><span class="hint">Dicatat otomatis & masuk buku kas.</span></div>
  </div>
  <div class="mfoot"><button type="button" class="btn" onclick="closeModal('mForm')">Batal</button><button class="btn btn-primary">Simpan</button></div>
</form></div></div>
@endsection
@section('scripts')
<script>
var NEXT="{{ $next }}", STORE="{{ route('anggota.store') }}", UPD="{{ url('anggota') }}";
function openForm(){document.getElementById('mTitle').textContent='Tambah Anggota';document.getElementById('fEl').action=STORE;
 f_no.value=NEXT;f_pin.value='';f_nama.value='';f_tel.value='';f_al.value='';document.getElementById('f_pokokWrap').style.display='';openModal('mForm');}
function editRow(m){document.getElementById('mTitle').textContent='Ubah Anggota';document.getElementById('fEl').action=UPD+'/'+m.id+'/update';
 f_no.value=m.no_anggota;f_pin.value=m.pin;f_nama.value=m.nama;f_tel.value=m.telepon||'';f_al.value=m.alamat||'';document.getElementById('f_pokokWrap').style.display='none';openModal('mForm');}
</script>
@endsection

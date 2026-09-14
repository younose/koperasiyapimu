@extends('layouts.app')
@section('content')
@php($fp=rtrim(rtrim(number_format((float)setting('faktor_plafon',2),2),'0'),'.'))
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-blue">{!! icon('piggy') !!}</div><div><div class="stat-l">Simpanan Sukarela</div><div class="stat-v">{{ rupiah($suk) }}</div><div class="stat-s">dasar plafon</div></div></div>
  <div class="stat"><div class="stat-ic t-gold">{!! icon('loan') !!}</div><div><div class="stat-l">Plafon ({{ $fp }}×)</div><div class="stat-v">{{ rupiah($plafon) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-red">{!! icon('out') !!}</div><div><div class="stat-l">Sedang Dipinjam</div><div class="stat-v">{{ rupiah($out) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-green">{!! icon('check') !!}</div><div><div class="stat-l">Sisa Plafon</div><div class="stat-v gold">{{ rupiah($sisaP) }}</div></div></div>
</div>
<div class="bar"><div class="note" style="flex:1">{!! icon('info') !!} Ajukan pinjaman maksimal sebesar sisa plafon. Pengurus akan meninjau pengajuan Anda.</div>
  <button class="btn btn-primary" onclick="openModal('mA')" {{ $sisaP<=0?'disabled':'' }}>{!! icon('plus') !!} Ajukan Pinjaman</button></div>
<section class="panel np"><div class="phead pad"><h2>Riwayat Pinjaman Saya</h2></div>
<table><thead><tr><th>Tgl Ajuan</th><th class="r">Pokok</th><th class="c">Tenor</th><th class="r">Sisa</th><th>Status</th></tr></thead><tbody>
@forelse($list as $p)
  @php($badge=['diajukan'=>'p-warn','aktif'=>'p-ok','lunas'=>'p-grey','ditolak'=>'p-bad'][$p->status])
  <tr><td>{{ tgl_id($p->tgl_ajukan) }}</td><td class="r mono">{{ rupiah($p->jumlah_pokok) }}</td><td class="c">{{ $p->tenor }} bln</td>
    <td class="r mono">{{ $p->status==='aktif'?rupiah($p->sisa()):'—' }}</td>
    <td><span class="pill {{ $badge }}" style="text-transform:capitalize">{{ $p->status }}</span></td></tr>
@empty<tr><td colspan="5" class="empty">Belum ada pinjaman.</td></tr>@endforelse
</tbody></table></section>
<div class="overlay" id="mA" style="display:none"><div class="modal"><form method="post" action="{{ route('pinjaman-saya.ajukan') }}">@csrf
  <div class="mhead"><h3>Ajukan Pinjaman</h3><button type="button" class="ic" onclick="closeModal('mA')">{!! icon('x') !!}</button></div>
  <div class="mbody">
    <div class="alert alert-ok">Sisa plafon Anda: <b>{{ rupiah($sisaP) }}</b></div>
    <div class="grid2">
      <div class="field"><label>Jumlah (Rp)</label><input type="number" name="jumlah" max="{{ (int)$sisaP }}" required></div>
      <div class="field"><label>Tenor (bulan)</label><input type="number" name="tenor" value="6" required></div>
    </div>
    <div class="field"><label>Keperluan</label><input type="text" name="keterangan" placeholder="mis. modal usaha"></div>
  </div>
  <div class="mfoot"><button type="button" class="btn" onclick="closeModal('mA')">Batal</button><button class="btn btn-primary">Kirim Pengajuan</button></div>
</form></div></div>
@endsection

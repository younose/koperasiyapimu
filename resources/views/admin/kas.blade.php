@extends('layouts.app')
@section('content')
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-blue">{!! icon('in') !!}</div><div><div class="stat-l">Total Kas Masuk</div><div class="stat-v">{{ rupiah($ks['masuk']) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-red">{!! icon('out') !!}</div><div><div class="stat-l">Total Kas Keluar</div><div class="stat-v">{{ rupiah($ks['keluar']) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-gold">{!! icon('wallet') !!}</div><div><div class="stat-l">Saldo Kas</div><div class="stat-v">{{ rupiah($ks['saldo']) }}</div><div class="stat-s">masuk − keluar</div></div></div>
</div>
<div class="bar">
  <form class="search" method="get">{!! icon('search') !!}<input type="text" name="q" value="{{ $q }}" placeholder="Cari keterangan / kategori…"><input type="hidden" name="f" value="{{ $f }}"></form>
  <div style="display:flex;gap:6px">
    @foreach(['semua'=>'Semua','masuk'=>'Masuk','keluar'=>'Keluar'] as $k=>$l)
      <a class="btn btn-sm{{ $f===$k?' btn-primary':'' }}" href="{{ route('kas.index',['f'=>$k,'q'=>$q]) }}">{{ $l }}</a>
    @endforeach
  </div>
  <button class="btn btn-primary" onclick="openModal('mK')">{!! icon('plus') !!} Catat Kas</button>
</div>
<section class="panel np">
<table><thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th class="r">Masuk</th><th class="r">Keluar</th><th class="r">Saldo</th><th></th></tr></thead><tbody>
@forelse($rows as $e)
  <tr><td>{{ tgl_id($e['tgl']) }}</td><td class="cs">{{ $e['ket'] }}</td><td><span class="tag">{{ $e['kategori'] }}</span></td>
    <td class="r mono pos">{{ $e['arah']==='masuk'?rupiah($e['jumlah']):'' }}</td>
    <td class="r mono neg">{{ $e['arah']==='keluar'?rupiah($e['jumlah']):'' }}</td>
    <td class="r mono">{{ rupiah($e['saldo']) }}</td>
    <td class="r">@if($e['sumber']==='kas')<form method="post" action="{{ route('kas.destroy',$e['id']) }}" onsubmit="return confirm('Hapus?')">@csrf<button class="ic d">{!! icon('trash') !!}</button></form>
      @else<span class="ic" title="Otomatis dari {{ $e['sumber'] }}">{!! icon('lock') !!}</span>@endif</td></tr>
@empty<tr><td colspan="7" class="empty">Belum ada mutasi.</td></tr>@endforelse
</tbody></table>
</section>
<p class="note">{!! icon('lock') !!} Baris terkunci otomatis dari Simpanan / Pinjaman — ubah lewat menunya masing-masing.</p>
<div class="overlay" id="mK" style="display:none"><div class="modal"><form method="post" action="{{ route('kas.store') }}">@csrf
  <div class="mhead"><h3>Catat Kas</h3><button type="button" class="ic" onclick="closeModal('mK')">{!! icon('x') !!}</button></div>
  <div class="mbody">
    <div class="grid2">
      <div class="field"><label>Jenis</label><select name="arah"><option value="masuk">Kas Masuk</option><option value="keluar">Kas Keluar</option></select></div>
      <div class="field"><label>Tanggal</label><input type="date" name="tgl" value="{{ date('Y-m-d') }}"></div>
    </div>
    <div class="field"><label>Kategori</label><input type="text" name="kategori" list="kat" placeholder="mis. Iuran, Listrik, ATK"><datalist id="kat"><option>Iuran</option><option>Bunga Bank</option><option>Donasi</option><option>Biaya Operasional</option><option>ATK</option><option>Listrik & Air</option></datalist></div>
    <div class="field"><label>Jumlah (Rp)</label><input type="number" name="jumlah" required></div>
    <div class="field"><label>Keterangan</label><input type="text" name="keterangan"></div>
  </div>
  <div class="mfoot"><button type="button" class="btn" onclick="closeModal('mK')">Batal</button><button class="btn btn-primary">Simpan</button></div>
</form></div></div>
@endsection

@extends('layouts.app')
@section('content')
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-green">{!! icon('users') !!}</div><div><div class="stat-l">Total Anggota</div><div class="stat-v">{{ angka($jml_anggota) }}</div><div class="stat-s">anggota aktif</div></div></div>
  <div class="stat"><div class="stat-ic t-gold">{!! icon('wallet') !!}</div><div><div class="stat-l">Total Simpanan</div><div class="stat-v">{{ rupiah(\App\Models\Simpanan::totalAll()) }}</div><div class="stat-s">pokok+wajib+sukarela</div></div></div>
  <div class="stat"><div class="stat-ic t-blue">{!! icon('loan') !!}</div><div><div class="stat-l">Pinjaman Aktif</div><div class="stat-v">{{ angka($pinj_aktif) }}</div><div class="stat-s">outstanding {{ rupiah($outstanding) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-red">{!! icon('bell') !!}</div><div><div class="stat-l">Anggota Nunggak</div><div class="stat-v">{{ angka($nunggak) }}</div><div class="stat-s">iuran belum dibayar</div></div></div>
</div>
<div class="two">
  <section class="panel">
    <div class="phead"><h2>Ringkasan Kas</h2></div>
    <div class="bars">
      <div class="row"><div>Kas Masuk</div><div class="track"><div class="fill" style="width:100%"></div></div><div class="mono pos">{{ rupiah($ks['masuk']) }}</div></div>
      <div class="row"><div>Kas Keluar</div><div class="track"><div class="fill" style="width:{{ $ks['masuk']?round($ks['keluar']/$ks['masuk']*100):0 }}%;background:linear-gradient(90deg,#b3402f,#d8654c)"></div></div><div class="mono neg">{{ rupiah($ks['keluar']) }}</div></div>
    </div>
    <div class="saldobox"><span>Saldo Kas Saat Ini</span><b class="mono">{{ rupiah($ks['saldo']) }}</b></div>
  </section>
  <section class="panel np">
    <div class="phead pad"><h2>Mutasi Terbaru</h2><a class="btn btn-sm" href="{{ route('kas.index') }}">Buku kas</a></div>
    <table><thead><tr><th>Tanggal</th><th>Keterangan</th><th class="r">Nilai</th></tr></thead><tbody>
    @forelse($recent as $e)
      <tr><td>{{ tgl_id($e['tgl']) }}</td><td><div class="cs">{{ $e['ket'] }}</div><div class="csub">{{ $e['kategori'] }}</div></td>
      <td class="r mono {{ $e['arah']==='masuk'?'pos':'neg' }}">{{ $e['arah']==='masuk'?'+':'−' }}{{ rupiah($e['jumlah']) }}</td></tr>
    @empty<tr><td colspan="3" class="empty">Belum ada mutasi.</td></tr>@endforelse
    </tbody></table>
  </section>
</div>
@endsection

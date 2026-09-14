@extends('layouts.app')
@section('content')
<div class="bar no-print">
  <form method="get" style="display:flex;gap:8px;align-items:center">
    <label style="font-size:13px;font-weight:600">Periode</label>
    <select name="periode" onchange="this.form.submit()">
      @foreach($periods as $p)<option value="{{ $p }}" {{ $p===$per?'selected':'' }}>{{ bulan_label($p) }}</option>@endforeach</select>
  </form>
  <button class="btn btn-primary" onclick="window.print()">{!! icon('print') !!} Cetak / Simpan PDF</button>
</div>
<div class="rep-head">
  <img src="{{ asset('logo.svg') }}" alt="logo">
  <div><div class="knm">{{ nama_koperasi() }}</div><div class="ksub">Laporan Kegiatan &amp; Keuangan Bulanan</div></div>
  <div class="rep-title"><div class="t">Periode {{ bulan_label($per) }}</div><div class="p">{{ tgl_id($r['awal']) }} – {{ tgl_id($r['akhir']) }}</div></div>
</div>
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-green">{!! icon('users') !!}</div><div><div class="stat-l">Anggota Aktif</div><div class="stat-v">{{ angka($jml_anggota) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-blue">{!! icon('in') !!}</div><div><div class="stat-l">Kas Masuk (bulan ini)</div><div class="stat-v">{{ rupiah($r['kas_masuk']) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-red">{!! icon('out') !!}</div><div><div class="stat-l">Kas Keluar (bulan ini)</div><div class="stat-v">{{ rupiah($r['kas_keluar']) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-gold">{!! icon('wallet') !!}</div><div><div class="stat-l">Saldo Kas Akhir</div><div class="stat-v">{{ rupiah($saldoKasTotal) }}</div><div class="stat-s">kumulatif s/d kini</div></div></div>
</div>
<div class="two">
  <section class="panel np"><div class="phead pad"><h2>Rekap Simpanan Bulan Ini</h2></div>
    <table><thead><tr><th>Jenis</th><th class="r">Setoran</th><th class="r">Penarikan</th><th class="r">Netto</th></tr></thead><tbody>
    @php($ts=0)@php($tt=0)
    @foreach(['Pokok','Wajib','Sukarela'] as $j)
      @php($se=$r['simp_setor'][$j])@php($ta=$r['simp_tarik'][$j])@php($ts+=$se)@php($tt+=$ta)
      <tr><td>{{ $j }}</td><td class="r mono pos">{{ rupiah($se) }}</td><td class="r mono neg">{{ rupiah($ta) }}</td><td class="r mono">{{ rupiah($se-$ta) }}</td></tr>
    @endforeach
    </tbody><tfoot><tr><td>Jumlah</td><td class="r mono">{{ rupiah($ts) }}</td><td class="r mono">{{ rupiah($tt) }}</td><td class="r mono gold">{{ rupiah($ts-$tt) }}</td></tr></tfoot></table>
  </section>
  <section class="panel"><div class="phead"><h2>Pinjaman &amp; Iuran</h2></div>
    <table style="font-family:inherit"><tbody>
      <tr><td>Pinjaman dicairkan</td><td class="r mono">{{ rupiah($r['pinjaman_cair']) }}</td></tr>
      <tr><td>Angsuran diterima</td><td class="r mono">{{ rupiah($r['angsuran_masuk']) }}</td></tr>
      <tr><td>Iuran wajib — lunas</td><td class="r"><span class="pill p-ok">{{ $r['iuran_lunas'] }} anggota</span></td></tr>
      <tr><td>Iuran wajib — belum</td><td class="r"><span class="pill p-bad">{{ $r['iuran_belum'] }} anggota</span></td></tr>
    </tbody></table>
    <p class="note">{!! icon('info') !!} Netto simpanan bulan ini: <b>{{ rupiah($ts-$tt) }}</b>. Total simpanan kumulatif: <b>{{ rupiah($totalSimpanan) }}</b>.</p>
  </section>
</div>
<section class="panel np"><div class="phead pad"><h2>Mutasi Kas — {{ bulan_label($per) }}</h2></div>
<table><thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th class="r">Masuk</th><th class="r">Keluar</th></tr></thead><tbody>
@forelse($mut as $e)
  <tr><td class="nowrap">{{ tgl_id($e['tgl']) }}</td><td>{{ $e['ket'] }}</td><td><span class="tag">{{ $e['kategori'] }}</span></td>
    <td class="r mono pos">{{ $e['arah']==='masuk'?rupiah($e['jumlah']):'' }}</td>
    <td class="r mono neg">{{ $e['arah']==='keluar'?rupiah($e['jumlah']):'' }}</td></tr>
@empty<tr><td colspan="5" class="empty">Tidak ada mutasi pada periode ini.</td></tr>@endforelse
</tbody><tfoot><tr><td colspan="3">Jumlah</td><td class="r mono pos">{{ rupiah($r['kas_masuk']) }}</td><td class="r mono neg">{{ rupiah($r['kas_keluar']) }}</td></tr></tfoot></table>
</section>
<div class="two" style="margin-top:26px">
  <div class="c" style="font-size:13px">Mengetahui,<br>Ketua Koperasi<br><br><br>( ................................ )</div>
  <div class="c" style="font-size:13px">Dibuat oleh,<br>Bendahara / Pengurus<br><br><br>( ................................ )</div>
</div>
@endsection

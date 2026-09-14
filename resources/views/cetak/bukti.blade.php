<!doctype html><html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1"><title>Bukti Simpanan — {{ nama_koperasi() }}</title>
@include('cetak._style')</head><body>
<div class="toolbar">
  <a class="btn" href="{{ is_admin()?route('simpanan.index'):route('portal') }}">← Kembali</a>
  <button class="btn btn-primary" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
</div>
<div class="sheet">
  <div class="rhead"><img src="{{ asset('logo.svg') }}" alt="logo">
    <div><div class="knm">{{ nama_koperasi() }}</div><div class="ksub">Bukti transaksi resmi simpanan anggota</div></div></div>
  <div class="title">{{ $s->arah==='tarik'?'Bukti Penarikan':'Bukti Setoran' }} Simpanan</div>
  <div class="meta"><span>No. Bukti: <b>BKT-{{ str_pad($s->id,5,'0',STR_PAD_LEFT) }}</b></span><span>Tanggal: <b>{{ tgl_id($s->tgl) }}</b></span></div>
  <table class="kv">
    <tr><td>Nama Anggota</td><td><b>{{ $s->anggota->nama }}</b> ({{ $s->anggota->no_anggota }})</td></tr>
    <tr><td>Jenis Simpanan</td><td>{{ $s->jenis }}</td></tr>
    <tr><td>Jenis Transaksi</td><td>{{ $s->arah==='tarik'?'Penarikan (keluar)':'Setoran (masuk)' }}</td></tr>
    @if($s->catatan)<tr><td>Keterangan</td><td>{{ $s->catatan }}</td></tr>@endif
    <tr><td>Jumlah</td><td><span class="big">{{ rupiah($s->jumlah) }}</span></td></tr>
  </table>
  <div class="terbilang">Terbilang: <b>{{ terbilang_rupiah($s->jumlah) }}</b></div>
  <div class="sign">
    <div class="box"><div>Penyetor,</div><div class="line">{{ $s->anggota->nama }}</div></div>
    <div class="box"><div>Petugas Koperasi,</div><div class="line">( ................................ )</div></div>
  </div>
  <p class="note">Dokumen dicetak dari sistem {{ nama_koperasi() }} pada {{ date('d M Y H:i') }} WIB.</p>
</div>
</body></html>

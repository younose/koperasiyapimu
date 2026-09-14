<!doctype html><html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1"><title>Kartu Anggota — {{ nama_koperasi() }}</title>
@include('cetak._style')</head><body>
<div class="toolbar">
  <a class="btn" href="{{ is_admin()?route('anggota.index'):route('portal') }}">← Kembali</a>
  <button class="btn btn-primary" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
</div>
<div class="card">
  <div class="top"><img src="{{ asset('logo.svg') }}" alt="logo">
    <div><div class="n">{{ nama_koperasi() }}</div><div class="s">KARTU TANDA ANGGOTA</div></div></div>
  <div class="body">
    <div>
      <div class="f"><div class="l">Nomor Anggota</div><div class="no">{{ $a->no_anggota }}</div></div>
      <div class="f"><div class="l">Nama Anggota</div><div class="v">{{ $a->nama }}</div></div>
      <div class="f"><div class="l">Tanggal Bergabung</div><div class="v">{{ tgl_id($a->tgl_gabung) }}</div></div>
    </div>
    <div style="text-align:right">
      <div class="f"><div class="l">Telepon</div><div class="v">{{ $a->telepon ?: '—' }}</div></div>
      <div class="f"><div class="l">Status</div><div class="v">{{ $a->aktif?'Aktif':'Nonaktif' }}</div></div>
    </div>
  </div>
  <div class="foot">{{ nama_koperasi() }} · Kartu ini sah sebagai identitas keanggotaan</div>
</div>
<p class="note">Simpan sebagai PDF: pada dialog cetak pilih tujuan "Save as PDF".</p>
</body></html>

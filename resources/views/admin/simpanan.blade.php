@extends('layouts.app')
@section('content')
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-gold">{!! icon('piggy') !!}</div><div><div class="stat-l">Simpanan Pokok</div><div class="stat-v">{{ rupiah(\App\Models\Simpanan::totalJenis('Pokok')) }}</div><div class="stat-s">sekali saat masuk</div></div></div>
  <div class="stat"><div class="stat-ic t-green">{!! icon('wallet') !!}</div><div><div class="stat-l">Simpanan Wajib</div><div class="stat-v">{{ rupiah(\App\Models\Simpanan::totalJenis('Wajib')) }}</div><div class="stat-s">rutin tiap bulan</div></div></div>
  <div class="stat"><div class="stat-ic t-blue">{!! icon('piggy') !!}</div><div><div class="stat-l">Simpanan Sukarela</div><div class="stat-v">{{ rupiah(\App\Models\Simpanan::totalJenis('Sukarela')) }}</div><div class="stat-s">dasar plafon pinjaman</div></div></div>
</div>
<div class="two">
  <section class="panel np">
    <div class="phead pad"><h2>Saldo per Anggota</h2></div>
    <table><thead><tr><th>Anggota</th><th class="r">Pokok</th><th class="r">Wajib</th><th class="r">Sukarela</th><th class="r">Total</th></tr></thead><tbody>
    @foreach($members as $m)
      <tr><td><div class="cs">{{ $m->nama }}</div><div class="csub">{{ $m->no_anggota }}</div></td>
      <td class="r mono">{{ rupiah($m->saldoJenis('Pokok')) }}</td>
      <td class="r mono">{{ rupiah($m->saldoJenis('Wajib')) }}</td>
      <td class="r mono">{{ rupiah($m->saldoJenis('Sukarela')) }}</td>
      <td class="r mono gold">{{ rupiah($m->totalSimpanan()) }}</td></tr>
    @endforeach
    </tbody></table>
  </section>
  <section class="panel np">
    <div class="phead pad"><h2>Riwayat Setor / Tarik</h2><button class="btn btn-primary btn-sm" onclick="openModal('mS')">{!! icon('plus') !!} Catat</button></div>
    <table><thead><tr><th>Tanggal</th><th>Anggota</th><th>Jenis</th><th class="r">Nilai</th><th></th></tr></thead><tbody>
    @foreach($hist as $s)
      <tr><td>{{ tgl_id($s->tgl) }}</td>
        <td><div class="cs">{{ $s->anggota->nama ?? '-' }}</div>@if($s->catatan)<div class="csub">{{ $s->catatan }}</div>@endif</td>
        <td><span class="tag">{{ $s->jenis }}</span><span class="tag2 {{ $s->arah==='tarik'?'t-out':'t-in' }}">{{ $s->arah==='tarik'?'Tarik':'Setor' }}</span></td>
        <td class="r mono {{ $s->arah==='tarik'?'neg':'pos' }}">{{ $s->arah==='tarik'?'−':'+' }}{{ rupiah($s->jumlah) }}</td>
        <td class="r nowrap"><a class="ic" href="{{ route('cetak.bukti',$s) }}" target="_blank" title="Cetak bukti">{!! icon('print') !!}</a>
          <form method="post" action="{{ route('simpanan.destroy',$s) }}" style="display:inline" onsubmit="return confirm('Hapus catatan ini?')">@csrf<button class="ic d">{!! icon('trash') !!}</button></form></td></tr>
    @endforeach
    </tbody></table>
  </section>
</div>
<div class="overlay" id="mS" style="display:none"><div class="modal"><form method="post" action="{{ route('simpanan.store') }}">@csrf
  <div class="mhead"><h3>Catat Simpanan</h3><button type="button" class="ic" onclick="closeModal('mS')">{!! icon('x') !!}</button></div>
  <div class="mbody">
    <div class="field"><label>Anggota</label><select name="anggota_id" required><option value="">— pilih —</option>
      @foreach($members as $m)<option value="{{ $m->id }}">{{ $m->no_anggota }} — {{ $m->nama }}</option>@endforeach</select></div>
    <div class="grid2">
      <div class="field"><label>Jenis</label><select name="jenis"><option>Wajib</option><option>Pokok</option><option>Sukarela</option></select></div>
      <div class="field"><label>Transaksi</label><select name="arah"><option value="setor">Setor (masuk)</option><option value="tarik">Tarik (keluar)</option></select></div>
    </div>
    <div class="grid2">
      <div class="field"><label>Tanggal</label><input type="date" name="tgl" value="{{ date('Y-m-d') }}"></div>
      <div class="field"><label>Jumlah (Rp)</label><input type="number" name="jumlah" value="25000" required></div>
    </div>
    <div class="field"><label>Catatan (opsional)</label><input type="text" name="catatan"></div>
  </div>
  <div class="mfoot"><button type="button" class="btn" onclick="closeModal('mS')">Batal</button><button class="btn btn-primary">Simpan</button></div>
</form></div></div>
@endsection

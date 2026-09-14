@extends('layouts.app')
@section('content')
@if($belum->count())
<div class="alert alert-warn">{!! icon('bell') !!} Anda memiliki <b>{{ $belum->count() }}</b> bulan iuran wajib yang belum dibayar: 
  @foreach($belum as $b){{ bulan_label($b->periode) }}@if(!$loop->last), @endif @endforeach. Silakan hubungi pengurus.</div>
@endif
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-gold">{!! icon('piggy') !!}</div><div><div class="stat-l">Simpanan Pokok</div><div class="stat-v">{{ rupiah($pokok) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-green">{!! icon('wallet') !!}</div><div><div class="stat-l">Simpanan Wajib</div><div class="stat-v">{{ rupiah($wajib) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-blue">{!! icon('piggy') !!}</div><div><div class="stat-l">Simpanan Sukarela</div><div class="stat-v">{{ rupiah($suk) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-green">{!! icon('shu') !!}</div><div><div class="stat-l">Total Simpanan</div><div class="stat-v gold">{{ rupiah($pokok+$wajib+$suk) }}</div></div></div>
</div>
<div class="bar"><div class="note" style="flex:1">{!! icon('info') !!} Kartu & bukti setoran bisa dicetak / disimpan sebagai PDF.</div>
  <a class="btn" href="{{ route('cetak.kartu',$a) }}" target="_blank">{!! icon('card') !!} Cetak Kartu Anggota</a></div>
<section class="panel np"><div class="phead pad"><h2>Riwayat Simpanan Saya</h2></div>
<table><thead><tr><th>Tanggal</th><th>Jenis</th><th>Transaksi</th><th class="r">Nilai</th><th></th></tr></thead><tbody>
@forelse($hist as $s)
  <tr><td>{{ tgl_id($s->tgl) }}</td><td><span class="tag">{{ $s->jenis }}</span></td>
    <td>{{ $s->arah==='tarik'?'Penarikan':'Setoran' }}@if($s->catatan)<div class="csub">{{ $s->catatan }}</div>@endif</td>
    <td class="r mono {{ $s->arah==='tarik'?'neg':'pos' }}">{{ $s->arah==='tarik'?'−':'+' }}{{ rupiah($s->jumlah) }}</td>
    <td class="r"><a class="ic" href="{{ route('cetak.bukti',$s) }}" target="_blank" title="Cetak bukti">{!! icon('print') !!}</a></td></tr>
@empty<tr><td colspan="5" class="empty">Belum ada transaksi.</td></tr>@endforelse
</tbody></table></section>
@endsection

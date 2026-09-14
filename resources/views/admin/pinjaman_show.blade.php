@extends('layouts.app')
@section('content')
@php($sch=$p->angsuranBulanan())@php($sisa=$p->sisa())@php($bayar=$p->terbayarPokok())
<a class="btn btn-sm" href="{{ route('pinjaman.index') }}">← Kembali</a>
<div class="stat-grid" style="margin-top:14px">
  <div class="stat"><div class="stat-ic t-gold">{!! icon('loan') !!}</div><div><div class="stat-l">Pokok Pinjaman</div><div class="stat-v">{{ rupiah($p->jumlah_pokok) }}</div><div class="stat-s">{{ $p->anggota->nama }} · {{ $p->anggota->no_anggota }}</div></div></div>
  <div class="stat"><div class="stat-ic t-red">{!! icon('out') !!}</div><div><div class="stat-l">Sisa Pokok</div><div class="stat-v">{{ rupiah($sisa) }}</div><div class="stat-s">terbayar {{ rupiah($bayar) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-blue">{!! icon('shu') !!}</div><div><div class="stat-l">Angsuran / bln</div><div class="stat-v">{{ rupiah($sch['total']) }}</div><div class="stat-s">pokok {{ rupiah($sch['pokok']) }} + jasa {{ rupiah($sch['jasa']) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-green">{!! icon('info') !!}</div><div><div class="stat-l">Status</div><div class="stat-v" style="text-transform:capitalize">{{ $p->status }}</div><div class="stat-s">tenor {{ $p->tenor }} bln · jasa {{ rtrim(rtrim(number_format($p->jasa_persen,2),'0'),'.') }}%/bln</div></div></div>
</div>
@if($p->status==='aktif')
<div class="bar">
  <form method="post" action="{{ route('pinjaman.bayar',$p) }}">@csrf<button class="btn btn-primary">{!! icon('check') !!} Bayar 1 Angsuran ({{ rupiah($sch['total']) }})</button></form>
  <form method="post" action="{{ route('pinjaman.lunasi',$p) }}" onsubmit="return confirm('Lunasi sisa pokok {{ rupiah($sisa) }}?')">@csrf<button class="btn">Lunasi Sekarang</button></form>
</div>
@endif
<section class="panel np"><div class="phead pad"><h2>Riwayat Angsuran</h2></div>
  <table><thead><tr><th>Tanggal</th><th class="r">Pokok</th><th class="r">Jasa</th><th class="r">Total</th><th>Ket.</th></tr></thead><tbody>
  @forelse($p->angsuran()->orderBy('tgl')->orderBy('id')->get() as $g)
    <tr><td>{{ tgl_id($g->tgl) }}</td><td class="r mono">{{ rupiah($g->pokok_bagian) }}</td><td class="r mono">{{ rupiah($g->jasa_bagian) }}</td><td class="r mono cs">{{ rupiah($g->jumlah) }}</td><td class="csub">{{ $g->keterangan }}</td></tr>
  @empty<tr><td colspan="5" class="empty">Belum ada angsuran.</td></tr>@endforelse
  </tbody></table>
</section>
@endsection

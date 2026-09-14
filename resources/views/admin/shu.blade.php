@extends('layouts.app')
@section('content')
@php($pmL=rtrim(rtrim(number_format($pm,2),'0'),'.'))
@php($puL=rtrim(rtrim(number_format($pu,2),'0'),'.'))
@php($sisaPct=rtrim(rtrim(number_format(100-$pm-$pu,2),'0'),'.'))
<div class="two">
  <section class="panel">
    <div class="phead"><h2>Parameter Pembagian SHU</h2></div>
    <form method="post" action="{{ route('shu.setting') }}">@csrf<input type="hidden" name="tahun" value="{{ $tahun }}">
      <div class="field"><label>Jasa Modal — {{ $pmL }}% (proporsional simpanan)</label><input type="number" step="1" name="pct_modal" value="{{ $pm }}"></div>
      <div class="field"><label>Jasa Usaha — {{ $puL }}% (proporsional jasa pinjaman)</label><input type="number" step="1" name="pct_usaha" value="{{ $pu }}"></div>
      <button class="btn btn-primary btn-sm">Simpan Parameter</button>
    </form>
    <div class="saldobox"><span>Cadangan &amp; lainnya ({{ $sisaPct }}%)</span><b class="mono">{{ rupiah($poolL) }}</b></div>
  </section>
  <section class="panel">
    <div class="phead"><h2>Sumber SHU (Tahun {{ $tahun }})</h2></div>
    <form method="get" style="margin-bottom:10px;display:flex;gap:8px">
      <input type="number" name="tahun" value="{{ $tahun }}" style="max-width:110px">
      <input type="number" name="total" value="{{ round($totalSHU) }}" placeholder="Total SHU">
      <button class="btn btn-sm">Hitung</button>
    </form>
    <div class="bars">
      <div class="row"><div>Jasa Modal</div><div class="track"><div class="fill" style="width:{{ $pm }}%"></div></div><div class="mono">{{ rupiah($poolM) }}</div></div>
      <div class="row"><div>Jasa Usaha</div><div class="track"><div class="fill" style="width:{{ $pu }}%"></div></div><div class="mono">{{ rupiah($poolU) }}</div></div>
    </div>
    <p class="note">{!! icon('info') !!} Default total SHU = jasa pinjaman terkumpul tahun ini ({{ rupiah($jasaTotal) }}). Bisa diubah manual.</p>
    <form method="post" action="{{ route('shu.bagikan') }}" onsubmit="return confirm('Bagikan SHU ke simpanan sukarela tiap anggota? Tindakan ini menambah simpanan mereka.')">@csrf
      <input type="hidden" name="tahun" value="{{ $tahun }}"><input type="hidden" name="total_shu" value="{{ round($totalSHU) }}">
      <button class="btn btn-primary btn-block">Bagikan ke Simpanan Sukarela Anggota</button>
    </form>
  </section>
</div>
<section class="panel np"><div class="phead pad"><h2>Perhitungan SHU per Anggota</h2></div>
<table><thead><tr><th>Anggota</th><th class="r">Simpanan</th><th class="r">Jasa Pinjaman</th><th class="r">Jasa Modal</th><th class="r">Jasa Usaha</th><th class="r">Total SHU</th></tr></thead><tbody>
@foreach($dist as $d)
  <tr><td><div class="cs">{{ $d['nama'] }}</div><div class="csub">{{ $d['no'] }}</div></td>
    <td class="r mono">{{ rupiah($d['simp']) }}</td><td class="r mono">{{ rupiah($d['jasa']) }}</td>
    <td class="r mono">{{ rupiah($d['sm']) }}</td><td class="r mono">{{ rupiah($d['su']) }}</td>
    <td class="r mono gold">{{ rupiah($d['tot']) }}</td></tr>
@endforeach
</tbody><tfoot><tr><td>Jumlah</td><td class="r mono">{{ rupiah($tsimp) }}</td><td class="r mono">{{ rupiah($tjasa) }}</td><td class="r mono">{{ rupiah($poolM) }}</td><td class="r mono">{{ rupiah($poolU) }}</td><td class="r mono gold">{{ rupiah($poolM+$poolU) }}</td></tr></tfoot></table>
</section>
@endsection

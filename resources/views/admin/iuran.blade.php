@extends('layouts.app')
@section('content')
<div class="stat-grid">
  <div class="stat"><div class="stat-ic t-gold">{!! icon('bell') !!}</div><div><div class="stat-l">Iuran / Bulan</div><div class="stat-v">{{ rupiah(setting('iuran_nominal',25000)) }}</div><div class="stat-s">jatuh tempo tgl {{ setting('jatuh_tempo_awal',5) }}–{{ setting('jatuh_tempo_akhir',10) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-green">{!! icon('check') !!}</div><div><div class="stat-l">Sudah Bayar</div><div class="stat-v">{{ $lunas }}</div><div class="stat-s">{{ bulan_label($per) }}</div></div></div>
  <div class="stat"><div class="stat-ic t-red">{!! icon('info') !!}</div><div><div class="stat-l">Belum Bayar</div><div class="stat-v">{{ $belum }}</div><div class="stat-s">periode terpilih</div></div></div>
</div>
<div class="bar">
  <form method="get"><select name="periode" onchange="this.form.submit()">
    @foreach($periods as $p)<option value="{{ $p }}" {{ $p===$per?'selected':'' }}>{{ bulan_label($p) }}</option>@endforeach</select></form>
  <form method="post" action="{{ route('iuran.generate') }}" style="display:inline">@csrf<input type="hidden" name="periode" value="{{ $per }}"><button class="btn">{!! icon('plus') !!} Buat tagihan periode ini</button></form>
  <form method="post" action="{{ route('iuran.backfill') }}" style="display:inline" onsubmit="return confirm('Buat tagihan sejak bulan gabung s/d bulan ini?')">@csrf<button class="btn">Backfill s/d bulan ini</button></form>
</div>
<section class="panel np"><table><thead><tr><th>Anggota</th><th class="r">Nominal</th><th>Status</th><th>Tgl Bayar</th><th></th></tr></thead><tbody>
@foreach($rows as $r)
  <tr><td><div class="cs">{{ $r->nama }}</div><div class="csub">{{ $r->no_anggota }}</div></td>
    <td class="r mono">{{ rupiah($r->nominal ?? setting('iuran_nominal',25000)) }}</td>
    <td>@if($r->iid===null)<span class="pill p-grey">Belum ditagih</span>
        @elseif($r->status==='lunas')<span class="pill p-ok">{!! icon('check') !!} Lunas</span>
        @else<span class="pill p-bad">Belum bayar</span>@endif</td>
    <td class="csub">{{ $r->tgl_bayar?tgl_id($r->tgl_bayar):'—' }}</td>
    <td class="r">@if($r->iid!==null && $r->status==='belum')
      <form method="post" action="{{ route('iuran.bayar') }}">@csrf<input type="hidden" name="id" value="{{ $r->iid }}"><input type="hidden" name="periode" value="{{ $per }}"><button class="btn btn-sm btn-primary">Tandai Lunas</button></form>
    @endif</td></tr>
@endforeach
</tbody></table></section>
<p class="note">{!! icon('info') !!} "Tandai Lunas" otomatis menambah <b>simpanan wajib</b> anggota & masuk buku kas. Pop-up pengingat muncul otomatis bagi yang menunggak.</p>
@endsection

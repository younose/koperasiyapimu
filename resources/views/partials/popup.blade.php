@php
  $pop=null;
  if(is_member()){
    $a=\App\Models\Anggota::find(cu()['id']); $belum=$a?$a->iuranBelum():collect();
    if($belum->count()) $pop=['t'=>'member','title'=>'Pengingat Setoran Wajib','key'=>'m'.cu()['id'].'-'.periode_now(),'items'=>$belum];
  } elseif(is_admin()){
    $n=\App\Models\Iuran::nunggakCount();
    if($n>0){
      $rows=\Illuminate\Support\Facades\DB::table('iuran as i')->join('anggota as a','a.id','=','i.anggota_id')
        ->where('i.status','belum')->where('i.periode','<=',periode_now())->orderBy('i.periode')->orderBy('a.nama')
        ->limit(12)->select('a.nama','i.periode','i.nominal')->get();
      $pop=['t'=>'admin','title'=>'Pengingat Iuran Anggota','key'=>'a-'.date('Y-m-d'),'n'=>$n,'rows'=>$rows,'win'=>dalam_jendela_bayar()];
    }
  }
@endphp
@if($pop)
<div class="overlay" id="popup" data-key="{{ $pop['key'] }}" style="display:none">
  <div class="modal">
    <div class="mbody" style="text-align:center">
      <div class="reminder-ic">{!! icon('bell') !!}</div>
      <h3 style="margin:0 0 8px">{{ $pop['title'] }}</h3>
      <div style="text-align:left;font-size:13px">
        @if($pop['t']==='member')
          <p style="margin:0 0 4px">Halo <b>{{ cu()['nama'] }}</b>, berikut bulan yang <b>belum dibayar</b>:</p>
          <ul class="mlist">@foreach($pop['items'] as $it)<li><span>{{ bulan_label($it->periode) }}</span><b class="mono">{{ rupiah($it->nominal) }}</b></li>@endforeach</ul>
          <p class="note" style="margin-top:12px">{!! icon('info') !!} Jatuh tempo tgl {{ setting('jatuh_tempo_awal',5) }}–{{ setting('jatuh_tempo_akhir',10) }} tiap bulan. Silakan hubungi pengurus.</p>
        @else
          <p style="margin:0">Terdapat <b>{{ $pop['n'] }} anggota</b> yang belum menyetor iuran wajib.@if($pop['win']) <span class="pill p-warn">Masa bayar tgl {{ setting('jatuh_tempo_awal',5) }}–{{ setting('jatuh_tempo_akhir',10) }}</span>@endif</p>
          <ul class="mlist">@foreach($pop['rows'] as $it)<li><span>{{ $it->nama }} · {{ bulan_label($it->periode) }}</span><b class="mono">{{ rupiah($it->nominal) }}</b></li>@endforeach</ul>
          <p class="note" style="margin-top:12px">{!! icon('info') !!} Kelola di menu <b>Iuran Wajib</b>.</p>
        @endif
      </div>
    </div>
    <div class="mfoot"><button class="btn btn-primary" onclick="dismissPopup()">Mengerti</button></div>
  </div>
</div>
@endif

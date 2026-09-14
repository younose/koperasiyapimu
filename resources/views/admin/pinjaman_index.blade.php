@extends('layouts.app')
@section('content')
@php($fp=rtrim(rtrim(setting('faktor_plafon','2'),'0'),'.'))
@php($jp=rtrim(rtrim(setting('jasa_pinjaman','1'),'0'),'.'))
<div class="bar">
  <div class="note" style="flex:1">{!! icon('info') !!} Plafon pinjaman = simpanan sukarela × <b>{{ $fp }}×</b> · jasa <b>{{ $jp }}%/bln</b> (atur di Pengaturan).</div>
  <button class="btn btn-primary" onclick="openModal('mP')">{!! icon('plus') !!} Beri Pinjaman</button>
</div>
<section class="panel np"><table><thead><tr><th>Anggota</th><th class="r">Pokok</th><th class="c">Tenor</th><th class="r">Sisa</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($list as $p)
  @php($badge=['diajukan'=>'p-warn','aktif'=>'p-ok','lunas'=>'p-grey','ditolak'=>'p-bad'][$p->status])
  <tr><td><div class="cs">{{ $p->anggota->nama ?? '-' }}</div><div class="csub">{{ $p->anggota->no_anggota ?? '' }} · {{ tgl_id($p->tgl_ajukan) }}</div></td>
    <td class="r mono">{{ rupiah($p->jumlah_pokok) }}</td><td class="c">{{ $p->tenor }} bln</td>
    <td class="r mono">{{ $p->status==='aktif'?rupiah($p->sisa()):'—' }}</td>
    <td><span class="pill {{ $badge }}" style="text-transform:capitalize">{{ $p->status }}</span></td>
    <td class="r nowrap">
      @if($p->status==='diajukan')
        <form method="post" action="{{ route('pinjaman.approve',$p) }}" style="display:inline">@csrf<button class="btn btn-sm btn-primary">Setujui</button></form>
        <form method="post" action="{{ route('pinjaman.reject',$p) }}" style="display:inline" onsubmit="return confirm('Tolak pengajuan?')">@csrf<button class="btn btn-sm btn-danger">Tolak</button></form>
      @else<a class="btn btn-sm" href="{{ route('pinjaman.show',$p) }}">Detail</a>@endif
    </td></tr>
@empty<tr><td colspan="6" class="empty">Belum ada pinjaman.</td></tr>@endforelse
</tbody></table></section>
<div class="overlay" id="mP" style="display:none"><div class="modal"><form method="post" action="{{ route('pinjaman.store') }}">@csrf
  <div class="mhead"><h3>Beri Pinjaman</h3><button type="button" class="ic" onclick="closeModal('mP')">{!! icon('x') !!}</button></div>
  <div class="mbody">
    <div class="field"><label>Anggota</label><select name="anggota_id" id="pm" onchange="showPlaf()" required><option value="">— pilih —</option>
      @foreach($members as $m)<option value="{{ $m->id }}">{{ $m->no_anggota }} — {{ $m->nama }}</option>@endforeach</select></div>
    <div class="alert alert-ok" id="plafInfo" style="display:none"></div>
    <div class="grid2">
      <div class="field"><label>Jumlah pokok (Rp)</label><input type="number" name="jumlah" required></div>
      <div class="field"><label>Tenor (bulan)</label><input type="number" name="tenor" value="6" required></div>
    </div>
    <div class="field"><label>Keterangan</label><input type="text" name="keterangan"></div>
  </div>
  <div class="mfoot"><button type="button" class="btn" onclick="closeModal('mP')">Batal</button><button class="btn btn-primary">Cairkan</button></div>
</form></div></div>
@endsection
@section('scripts')
<script>
var PLAF=@json($plaf);
function showPlaf(){var id=document.getElementById('pm').value,el=document.getElementById('plafInfo');
 if(!id||!PLAF[id]){el.style.display='none';return;}
 el.style.display='';el.innerHTML='Simpanan sukarela: <b>'+rp(PLAF[id].sukarela)+'</b> · Sisa plafon: <b>'+rp(PLAF[id].sisa)+'</b>';}
</script>
@endsection

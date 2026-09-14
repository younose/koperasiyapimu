<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Anggota,Pinjaman,Angsuran};
class PinjamanController extends Controller {
  public function index(){
    $members=Anggota::where('aktif',1)->orderBy('no_anggota')->get();
    $plaf=[]; foreach($members as $m) $plaf[$m->id]=['sukarela'=>$m->saldoSukarela(),'sisa'=>$m->sisaPlafon()];
    $list=Pinjaman::with('anggota')->orderByRaw("FIELD(status,'diajukan','aktif','lunas','ditolak')")->orderByDesc('id')->get();
    return view('admin.pinjaman_index',['title'=>'Pinjaman','members'=>$members,'plaf'=>$plaf,'list'=>$list]);
  }
  public function show(Pinjaman $pinjaman){
    $pinjaman->load('anggota','angsuran');
    return view('admin.pinjaman_show',['title'=>'Detail Pinjaman','p'=>$pinjaman]);
  }
  public function store(Request $r){
    $d=$r->validate(['anggota_id'=>'required|exists:anggota,id','jumlah'=>'required|numeric|min:1','tenor'=>'required|integer|min:1','keterangan'=>'nullable']);
    $a=Anggota::find($d['anggota_id']);
    if($d['jumlah']>$a->sisaPlafon()) return back()->with('err','Melebihi plafon. Sisa plafon anggota: '.rupiah($a->sisaPlafon()));
    Pinjaman::create(['anggota_id'=>$a->id,'tgl_ajukan'=>date('Y-m-d'),'tgl_cair'=>date('Y-m-d'),
      'jumlah_pokok'=>$d['jumlah'],'tenor'=>$d['tenor'],'jasa_persen'=>(float)setting('jasa_pinjaman',1),'status'=>'aktif','keterangan'=>$r->keterangan]);
    return redirect()->route('pinjaman.index')->with('ok','Pinjaman dibuat & dicairkan (kas keluar).');
  }
  public function approve(Pinjaman $pinjaman){
    if($pinjaman->status==='diajukan'){
      if($pinjaman->jumlah_pokok>$pinjaman->anggota->sisaPlafon())
        return back()->with('err','Tidak bisa cair: melebihi plafon (sisa '.rupiah($pinjaman->anggota->sisaPlafon()).').');
      $pinjaman->update(['status'=>'aktif','tgl_cair'=>date('Y-m-d')]);
      return back()->with('ok','Pinjaman disetujui & dicairkan.');
    }
    return back();
  }
  public function reject(Pinjaman $pinjaman){
    if($pinjaman->status==='diajukan') $pinjaman->update(['status'=>'ditolak']);
    return back()->with('ok','Pengajuan ditolak.');
  }
  public function bayar(Pinjaman $pinjaman){
    if($pinjaman->status==='aktif'){
      $sch=$pinjaman->angsuranBulanan(); $sisa=$pinjaman->sisa();
      $pokok=min($sch['pokok'],$sisa); $jasa=$sch['jasa'];
      if($pokok+$jasa>0){
        Angsuran::create(['pinjaman_id'=>$pinjaman->id,'tgl'=>date('Y-m-d'),'jumlah'=>$pokok+$jasa,'pokok_bagian'=>$pokok,'jasa_bagian'=>$jasa,'keterangan'=>'Angsuran']);
        if($pinjaman->fresh()->sisa()<=0) $pinjaman->update(['status'=>'lunas']);
      }
      return redirect()->route('pinjaman.show',$pinjaman)->with('ok','Angsuran dicatat (kas masuk).');
    }
    return back();
  }
  public function lunasi(Pinjaman $pinjaman){
    if($pinjaman->status==='aktif'){
      $sisa=$pinjaman->sisa();
      if($sisa>0){
        Angsuran::create(['pinjaman_id'=>$pinjaman->id,'tgl'=>date('Y-m-d'),'jumlah'=>$sisa,'pokok_bagian'=>$sisa,'jasa_bagian'=>0,'keterangan'=>'Pelunasan']);
        $pinjaman->update(['status'=>'lunas']);
      }
      return redirect()->route('pinjaman.show',$pinjaman)->with('ok','Pinjaman dilunasi.');
    }
    return back();
  }
}

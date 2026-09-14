<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Anggota,Pinjaman};
class PinjamanSayaController extends Controller {
  public function index(){
    $a=Anggota::find(cu()['id']);
    return view('member.pinjaman_saya',[
      'title'=>'Pinjaman Saya','a'=>$a,
      'suk'=>$a->saldoSukarela(),'plafon'=>$a->plafon(),'out'=>$a->outstanding(),'sisaP'=>$a->sisaPlafon(),
      'list'=>$a->pinjaman()->orderByDesc('id')->get(),
    ]);
  }
  public function ajukan(Request $r){
    $a=Anggota::find(cu()['id']);
    $d=$r->validate(['jumlah'=>'required|numeric|min:1','tenor'=>'required|integer|min:1','keterangan'=>'nullable']);
    if($d['jumlah']>$a->sisaPlafon()) return back()->with('err','Melebihi plafon. Sisa plafon Anda: '.rupiah($a->sisaPlafon()));
    Pinjaman::create(['anggota_id'=>$a->id,'tgl_ajukan'=>date('Y-m-d'),'jumlah_pokok'=>$d['jumlah'],'tenor'=>$d['tenor'],
      'jasa_persen'=>(float)setting('jasa_pinjaman',1),'status'=>'diajukan','keterangan'=>$r->keterangan]);
    return redirect()->route('pinjaman-saya')->with('ok','Pengajuan pinjaman terkirim. Menunggu persetujuan pengurus.');
  }
}

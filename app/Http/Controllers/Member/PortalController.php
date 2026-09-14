<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\{Anggota,Iuran};
class PortalController extends Controller {
  public function index(){
    Iuran::ensureCurrentAll();
    $a=Anggota::find(cu()['id']);
    return view('member.portal',[
      'title'=>'Simpanan Saya','a'=>$a,
      'pokok'=>$a->saldoJenis('Pokok'),'wajib'=>$a->saldoJenis('Wajib'),'suk'=>$a->saldoJenis('Sukarela'),
      'hist'=>$a->simpanan()->orderByDesc('tgl')->orderByDesc('id')->limit(25)->get(),
      'belum'=>$a->iuranBelum(),
    ]);
  }
}

<?php
namespace App\Http\Controllers;
use App\Models\{Anggota,Simpanan};
class CetakController extends Controller {
  public function kartu(Anggota $anggota){
    if(is_member() && cu()['id']!=$anggota->id) abort(403);
    return view('cetak.kartu',['a'=>$anggota]);
  }
  public function bukti(Simpanan $simpanan){
    $simpanan->load('anggota');
    if(is_member() && cu()['id']!=$simpanan->anggota_id) abort(403);
    return view('cetak.bukti',['s'=>$simpanan]);
  }
}

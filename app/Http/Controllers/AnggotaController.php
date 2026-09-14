<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Anggota,Simpanan};
class AnggotaController extends Controller {
  public function index(Request $r){
    $q=trim($r->q??'');
    $rows=Anggota::when($q,fn($x)=>$x->where(fn($w)=>$w->where('nama','like',"%$q%")->orWhere('no_anggota','like',"%$q%")->orWhere('telepon','like',"%$q%")))
      ->orderBy('no_anggota')->get();
    $next='AGT-'.str_pad(Anggota::count()+1,3,'0',STR_PAD_LEFT);
    return view('admin.anggota',['title'=>'Anggota','rows'=>$rows,'q'=>$q,'next'=>$next]);
  }
  public function store(Request $r){
    $d=$r->validate(['no'=>'required','nama'=>'required','telepon'=>'nullable','alamat'=>'nullable','pin'=>'nullable','pokok'=>'nullable|numeric']);
    $a=Anggota::create(['no_anggota'=>$d['no'],'nama'=>$d['nama'],'telepon'=>$r->telepon,'alamat'=>$r->alamat,
      'pin'=>$r->pin?:'1234','tgl_gabung'=>date('Y-m-d'),'aktif'=>1]);
    if((float)$r->pokok>0) Simpanan::create(['anggota_id'=>$a->id,'tgl'=>date('Y-m-d'),'jenis'=>'Pokok','arah'=>'setor','jumlah'=>(float)$r->pokok,'catatan'=>'Simpanan pokok saat mendaftar']);
    return redirect()->route('anggota.index')->with('ok','Anggota baru ditambahkan. ID login: '.$d['no']);
  }
  public function update(Request $r, Anggota $anggota){
    $d=$r->validate(['no'=>'required','nama'=>'required']);
    $anggota->update(['no_anggota'=>$d['no'],'nama'=>$d['nama'],'telepon'=>$r->telepon,'alamat'=>$r->alamat,'pin'=>$r->pin?:$anggota->pin]);
    return redirect()->route('anggota.index')->with('ok','Data anggota diperbarui.');
  }
  public function destroy(Anggota $anggota){
    $anggota->delete();
    return redirect()->route('anggota.index')->with('ok','Anggota dihapus.');
  }
}

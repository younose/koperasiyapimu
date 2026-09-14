<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
class AkunController extends Controller {
  public function index(){ return view('member.akun',['title'=>'Akun Saya','me'=>Anggota::find(cu()['id'])]); }
  public function updatePin(Request $r){
    $me=Anggota::find(cu()['id']);
    if(!hash_equals($me->pin,(string)($r->lama??''))) return redirect()->route('akun')->with('err','PIN lama salah.');
    if(strlen($r->baru??'')<4) return redirect()->route('akun')->with('err','PIN baru minimal 4 karakter.');
    if($r->baru!==$r->ulang) return redirect()->route('akun')->with('err','Konfirmasi PIN tidak sama.');
    $me->update(['pin'=>$r->baru]);
    return redirect()->route('akun')->with('ok','PIN berhasil diperbarui.');
  }
}

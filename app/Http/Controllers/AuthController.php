<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\{Admin,Anggota,Iuran};
class AuthController extends Controller {
  public function root(){ return cu()? redirect()->route(is_admin()?'dashboard':'portal') : redirect()->route('login'); }
  public function showLogin(){ return cu()? redirect()->route(is_admin()?'dashboard':'portal') : view('login'); }
  public function login(Request $r){
    $r->validate(['id'=>'required','pin'=>'required']);
    $id=trim($r->id); $pin=(string)$r->pin;
    $a=Admin::where('username',$id)->first();
    if($a && Hash::check($pin,$a->pass_hash)){
      $r->session()->regenerate();
      session(['kop_user'=>['role'=>'admin','id'=>$a->id,'nama'=>$a->nama]]);
      Iuran::ensureCurrentAll();
      return redirect()->route('dashboard');
    }
    $m=Anggota::where('no_anggota',$id)->where('aktif',1)->first();
    if($m && hash_equals($m->pin,$pin)){
      $r->session()->regenerate();
      session(['kop_user'=>['role'=>'member','id'=>$m->id,'nama'=>$m->nama,'no'=>$m->no_anggota]]);
      Iuran::ensureCurrentAll();
      return redirect()->route('portal');
    }
    return back()->with('err','ID Pengguna atau PIN salah.')->withInput();
  }
  public function logout(Request $r){ $r->session()->forget('kop_user'); $r->session()->regenerate(); return redirect()->route('login'); }
}

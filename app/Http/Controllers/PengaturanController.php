<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
class PengaturanController extends Controller {
  public function index(){ return view('admin.pengaturan',['title'=>'Pengaturan']); }
  public function updateUmum(Request $r){
    set_setting('nama_koperasi',trim($r->nama_koperasi));
    set_setting('iuran_nominal',(float)$r->iuran_nominal);
    set_setting('jatuh_tempo_awal',max(1,min(28,(int)$r->jatuh_tempo_awal)));
    set_setting('jatuh_tempo_akhir',max(1,min(28,(int)$r->jatuh_tempo_akhir)));
    set_setting('faktor_plafon',(float)$r->faktor_plafon);
    set_setting('jasa_pinjaman',(float)$r->jasa_pinjaman);
    return redirect()->route('pengaturan.index')->with('ok','Pengaturan disimpan.');
  }
  public function updatePassword(Request $r){
    $a=Admin::find(cu()['id']);
    if(!Hash::check($r->lama??'',$a->pass_hash)) return redirect()->route('pengaturan.index')->with('err','Password lama salah.');
    if(strlen($r->baru??'')<5) return redirect()->route('pengaturan.index')->with('err','Password baru minimal 5 karakter.');
    $a->update(['pass_hash'=>Hash::make($r->baru)]);
    return redirect()->route('pengaturan.index')->with('ok','Password diperbarui.');
  }
}

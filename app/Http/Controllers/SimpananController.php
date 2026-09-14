<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Anggota,Simpanan};
class SimpananController extends Controller {
  public function index(){
    return view('admin.simpanan',[
      'title'=>'Simpanan',
      'members'=>Anggota::where('aktif',1)->orderBy('no_anggota')->get(),
      'hist'=>Simpanan::with('anggota')->orderByDesc('tgl')->orderByDesc('id')->limit(40)->get(),
    ]);
  }
  public function store(Request $r){
    $d=$r->validate(['anggota_id'=>'required|exists:anggota,id','jenis'=>'required|in:Pokok,Wajib,Sukarela','arah'=>'required|in:setor,tarik','jumlah'=>'required|numeric|min:1','tgl'=>'nullable|date','catatan'=>'nullable']);
    Simpanan::create(['anggota_id'=>$d['anggota_id'],'tgl'=>$r->tgl?:date('Y-m-d'),'jenis'=>$d['jenis'],'arah'=>$d['arah'],'jumlah'=>$d['jumlah'],'catatan'=>$r->catatan]);
    return redirect()->route('simpanan.index')->with('ok','Transaksi simpanan dicatat & masuk ke buku kas.');
  }
  public function destroy(Simpanan $simpanan){
    $simpanan->delete();
    return redirect()->route('simpanan.index')->with('ok','Catatan simpanan dihapus.');
  }
}

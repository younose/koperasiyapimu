<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kas;
use App\Support\Ledger;
class KasController extends Controller {
  public function index(Request $r){
    $f=$r->f??'semua'; $q=strtolower(trim($r->q??''));
    $rows=array_reverse(Ledger::build());
    $rows=array_values(array_filter($rows,function($e)use($f,$q){
      return ($f==='semua'||$e['arah']===$f) && ($q===''||str_contains(strtolower($e['ket'].' '.$e['kategori']),$q));
    }));
    return view('admin.kas',['title'=>'Buku Kas','ks'=>Ledger::ringkas(),'rows'=>$rows,'f'=>$f,'q'=>$r->q??'']);
  }
  public function store(Request $r){
    $d=$r->validate(['arah'=>'required|in:masuk,keluar','jumlah'=>'required|numeric|min:1','kategori'=>'nullable','tgl'=>'nullable|date','keterangan'=>'nullable']);
    Kas::create(['tgl'=>$r->tgl?:date('Y-m-d'),'arah'=>$d['arah'],'kategori'=>$r->kategori?:($d['arah']==='masuk'?'Pemasukan':'Pengeluaran'),'jumlah'=>$d['jumlah'],'keterangan'=>$r->keterangan]);
    return redirect()->route('kas.index')->with('ok','Catatan kas ditambahkan.');
  }
  public function destroy(Kas $kas){
    $kas->delete();
    return redirect()->route('kas.index')->with('ok','Catatan kas dihapus.');
  }
}

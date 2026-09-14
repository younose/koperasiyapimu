<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Anggota,Iuran,Simpanan};
class IuranController extends Controller {
  public function index(Request $r){
    $per=$r->periode??periode_now();
    if(!preg_match('/^\d{4}-\d{2}$/',$per)) $per=periode_now();
    $rows=DB::table('anggota as a')->leftJoin('iuran as i',function($j)use($per){ $j->on('i.anggota_id','=','a.id')->where('i.periode','=',$per); })
      ->where('a.aktif',1)->orderBy('a.no_anggota')
      ->select('a.id as aid','a.no_anggota','a.nama','i.id as iid','i.status','i.nominal','i.tgl_bayar')->get();
    $periods=Iuran::select('periode')->distinct()->orderByDesc('periode')->pluck('periode')->toArray();
    if(!in_array($per,$periods)) array_unshift($periods,$per);
    $lunas=$rows->where('status','lunas')->count(); $belum=$rows->count()-$lunas;
    return view('admin.iuran',['title'=>'Iuran Wajib','per'=>$per,'rows'=>$rows,'periods'=>$periods,'lunas'=>$lunas,'belum'=>$belum]);
  }
  public function generate(Request $r){
    $per=$r->periode?:periode_now(); $nom=(float)setting('iuran_nominal',25000);
    foreach(Anggota::where('aktif',1)->pluck('id') as $id)
      Iuran::firstOrCreate(['anggota_id'=>$id,'periode'=>$per],['nominal'=>$nom,'status'=>'belum']);
    return redirect()->route('iuran.index',['periode'=>$per])->with('ok','Tagihan iuran '.bulan_label($per).' dibuat.');
  }
  public function backfill(){
    $nom=(float)setting('iuran_nominal',25000);
    foreach(Anggota::where('aktif',1)->get() as $a){
      $start=new \DateTime(date('Y-m-01',strtotime($a->tgl_gabung))); $end=new \DateTime(date('Y-m-01'));
      while($start<=$end){ Iuran::firstOrCreate(['anggota_id'=>$a->id,'periode'=>$start->format('Y-m')],['nominal'=>$nom,'status'=>'belum']); $start->modify('+1 month'); }
    }
    return redirect()->route('iuran.index')->with('ok','Tagihan dibuat sejak bulan gabung s/d bulan ini.');
  }
  public function bayar(Request $r){
    $i=Iuran::find($r->id);
    if($i && $i->status==='belum'){
      $s=Simpanan::create(['anggota_id'=>$i->anggota_id,'tgl'=>date('Y-m-d'),'jenis'=>'Wajib','arah'=>'setor','jumlah'=>$i->nominal,'catatan'=>'Iuran wajib '.bulan_label($i->periode)]);
      $i->update(['status'=>'lunas','tgl_bayar'=>date('Y-m-d'),'simpanan_id'=>$s->id]);
    }
    return redirect()->route('iuran.index',['periode'=>$r->periode])->with('ok','Iuran ditandai lunas & masuk simpanan wajib.');
  }
}

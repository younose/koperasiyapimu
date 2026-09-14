<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Anggota,Simpanan};
class ShuController extends Controller {
  private function jasaTahun($tahun){ return (float)DB::table('angsuran')->whereRaw('YEAR(tgl)=?',[$tahun])->sum('jasa_bagian'); }
  public function index(Request $r){
    $tahun=(int)($r->tahun??date('Y'));
    $jasaTotal=$this->jasaTahun($tahun);
    $pm=(float)setting('shu_pct_modal',25); $pu=(float)setting('shu_pct_usaha',40);
    $totalSHU=(float)($r->total??$jasaTotal);
    $poolM=$totalSHU*$pm/100; $poolU=$totalSHU*$pu/100; $poolL=$totalSHU-$poolM-$poolU;
    $tsimp=Simpanan::totalAll(); $tjasa=$jasaTotal; $dist=[];
    foreach(Anggota::where('aktif',1)->orderBy('no_anggota')->get() as $a){
      $simp=$a->totalSimpanan();
      $jasa=(float)DB::table('angsuran as g')->join('pinjaman as p','p.id','=','g.pinjaman_id')->where('p.anggota_id',$a->id)->whereRaw('YEAR(g.tgl)=?',[$tahun])->sum('g.jasa_bagian');
      $sm=$tsimp?$poolM*$simp/$tsimp:0; $su=$tjasa?$poolU*$jasa/$tjasa:0;
      $dist[]=['no'=>$a->no_anggota,'nama'=>$a->nama,'simp'=>$simp,'jasa'=>$jasa,'sm'=>$sm,'su'=>$su,'tot'=>$sm+$su];
    }
    usort($dist,fn($x,$y)=>$y['tot']<=>$x['tot']);
    return view('admin.shu',compact('tahun','jasaTotal','pm','pu','totalSHU','poolM','poolU','poolL','tsimp','tjasa','dist')+['title'=>'Pembagian SHU']);
  }
  public function updateSetting(Request $r){
    set_setting('shu_pct_modal',(float)$r->pct_modal); set_setting('shu_pct_usaha',(float)$r->pct_usaha);
    return redirect()->route('shu.index',['tahun'=>$r->tahun])->with('ok','Parameter SHU disimpan.');
  }
  public function bagikan(Request $r){
    $tahun=(int)$r->tahun; $total=(float)$r->total_shu;
    $pm=(float)setting('shu_pct_modal',25); $pu=(float)setting('shu_pct_usaha',40);
    $poolM=$total*$pm/100; $poolU=$total*$pu/100;
    $tsimp=Simpanan::totalAll(); $tjasa=$this->jasaTahun($tahun);
    foreach(Anggota::where('aktif',1)->get() as $a){
      $simp=$a->totalSimpanan();
      $jasa=(float)DB::table('angsuran as g')->join('pinjaman as p','p.id','=','g.pinjaman_id')->where('p.anggota_id',$a->id)->whereRaw('YEAR(g.tgl)=?',[$tahun])->sum('g.jasa_bagian');
      $val=round(($tsimp?$poolM*$simp/$tsimp:0)+($tjasa?$poolU*$jasa/$tjasa:0));
      if($val>0) Simpanan::create(['anggota_id'=>$a->id,'tgl'=>date('Y-m-d'),'jenis'=>'Sukarela','arah'=>'setor','jumlah'=>$val,'catatan'=>'Pembagian SHU tahun '.$tahun]);
    }
    return redirect()->route('shu.index',['tahun'=>$tahun])->with('ok','SHU dibagikan ke simpanan sukarela masing-masing anggota.');
  }
}

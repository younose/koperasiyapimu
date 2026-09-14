<?php
namespace App\Support;
use Illuminate\Support\Facades\DB;
class Laporan {
  public static function bulan($periode){
    $awal=$periode.'-01'; $akhir=date('Y-m-t',strtotime($awal));
    $r=['periode'=>$periode,'awal'=>$awal,'akhir'=>$akhir];
    foreach(['Pokok','Wajib','Sukarela'] as $j){
      $r['simp_setor'][$j]=(float)DB::table('simpanan')->where('jenis',$j)->where('arah','setor')->whereBetween('tgl',[$awal,$akhir])->sum('jumlah');
      $r['simp_tarik'][$j]=(float)DB::table('simpanan')->where('jenis',$j)->where('arah','tarik')->whereBetween('tgl',[$awal,$akhir])->sum('jumlah');
    }
    $masuk=0;$keluar=0;$mut=[];
    foreach(Ledger::build() as $e){ if($e['tgl']>=$awal && $e['tgl']<=$akhir){ $mut[]=$e; if($e['arah']==='masuk')$masuk+=$e['jumlah']; else $keluar+=$e['jumlah']; } }
    $r['kas_masuk']=$masuk; $r['kas_keluar']=$keluar; $r['mutasi']=$mut;
    $r['pinjaman_cair']=(float)DB::table('pinjaman')->whereBetween('tgl_cair',[$awal,$akhir])->sum('jumlah_pokok');
    $r['angsuran_masuk']=(float)DB::table('angsuran')->whereBetween('tgl',[$awal,$akhir])->sum('jumlah');
    $r['iuran_lunas']=(int)DB::table('iuran')->where('periode',$periode)->where('status','lunas')->count();
    $r['iuran_belum']=(int)DB::table('iuran')->where('periode',$periode)->where('status','belum')->count();
    return $r;
  }
}

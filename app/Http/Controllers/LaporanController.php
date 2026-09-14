<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Anggota,Simpanan};
use App\Support\{Laporan,Ledger};
class LaporanController extends Controller {
  public function index(Request $r){
    $per=$r->periode??periode_now();
    if(!preg_match('/^\d{4}-\d{2}$/',$per)) $per=periode_now();
    $data=Laporan::bulan($per);
    $periods=[]; for($i=0;$i<18;$i++) $periods[]=date('Y-m',strtotime("first day of -$i month"));
    return view('admin.laporan',[
      'title'=>'Laporan Bulanan','per'=>$per,'r'=>$data,
      'periods'=>$periods,'saldoKasTotal'=>Ledger::ringkas()['saldo'],
      'jml_anggota'=>Anggota::where('aktif',1)->count(),
      'totalSimpanan'=>Simpanan::totalAll(),
      'mut'=>array_reverse($data['mutasi']),
    ]);
  }
}

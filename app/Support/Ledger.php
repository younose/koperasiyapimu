<?php
namespace App\Support;
use Illuminate\Support\Facades\DB;
class Ledger {
  public static function build(){
    $rows=[];
    foreach(DB::table('simpanan as s')->join('anggota as a','a.id','=','s.anggota_id')
        ->select('s.tgl','s.arah','s.jenis','s.jumlah','s.catatan','a.nama')->get() as $r){
      $rows[]=['tgl'=>$r->tgl,'arah'=>$r->arah==='tarik'?'keluar':'masuk',
        'kategori'=>'Simpanan '.$r->jenis,'ket'=>$r->nama.($r->catatan?' — '.$r->catatan:''),
        'jumlah'=>(float)$r->jumlah,'sumber'=>'simpanan','id'=>null];
    }
    foreach(DB::table('pinjaman as p')->join('anggota as a','a.id','=','p.anggota_id')
        ->whereIn('p.status',['aktif','lunas'])->whereNotNull('p.tgl_cair')
        ->select('p.tgl_cair as tgl','p.jumlah_pokok as jumlah','a.nama')->get() as $r){
      $rows[]=['tgl'=>$r->tgl,'arah'=>'keluar','kategori'=>'Pencairan Pinjaman','ket'=>$r->nama,'jumlah'=>(float)$r->jumlah,'sumber'=>'pinjaman','id'=>null];
    }
    foreach(DB::table('angsuran as g')->join('pinjaman as p','p.id','=','g.pinjaman_id')->join('anggota as a','a.id','=','p.anggota_id')
        ->select('g.tgl','g.jumlah','a.nama')->get() as $r){
      $rows[]=['tgl'=>$r->tgl,'arah'=>'masuk','kategori'=>'Angsuran Pinjaman','ket'=>$r->nama,'jumlah'=>(float)$r->jumlah,'sumber'=>'angsuran','id'=>null];
    }
    foreach(DB::table('kas')->select('id','tgl','arah','kategori','jumlah','keterangan')->get() as $r){
      $rows[]=['tgl'=>$r->tgl,'arah'=>$r->arah,'kategori'=>$r->kategori,'ket'=>$r->keterangan,'jumlah'=>(float)$r->jumlah,'sumber'=>'kas','id'=>$r->id];
    }
    usort($rows,fn($a,$b)=>strcmp($a['tgl'],$b['tgl']));
    $run=0; foreach($rows as &$r){ $run+=$r['arah']==='masuk'?$r['jumlah']:-$r['jumlah']; $r['saldo']=$run; }
    return $rows;
  }
  public static function ringkas(){
    $m=0;$k=0; foreach(self::build() as $r){ if($r['arah']==='masuk')$m+=$r['jumlah']; else $k+=$r['jumlah']; }
    return ['masuk'=>$m,'keluar'=>$k,'saldo'=>$m-$k];
  }
}

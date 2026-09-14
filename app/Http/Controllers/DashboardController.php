<?php
namespace App\Http\Controllers;
use App\Models\{Anggota,Pinjaman,Iuran};
use App\Support\Ledger;
class DashboardController extends Controller {
  public function index(){
    Iuran::ensureCurrentAll();
    $ks=Ledger::ringkas();
    $outstanding=Pinjaman::where('status','aktif')->get()->sum(fn($p)=>$p->sisa());
    $recent=array_slice(array_reverse(Ledger::build()),0,7);
    return view('admin.dashboard',[
      'title'=>'Beranda',
      'jml_anggota'=>Anggota::where('aktif',1)->count(),
      'ks'=>$ks,
      'pinj_aktif'=>Pinjaman::where('status','aktif')->count(),
      'outstanding'=>$outstanding,
      'nunggak'=>Iuran::nunggakCount(),
      'recent'=>$recent,
    ]);
  }
}

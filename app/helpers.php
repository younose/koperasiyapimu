<?php
use App\Models\Pengaturan;

/* ---------- format ---------- */
function rupiah($n){ return 'Rp '.number_format((float)$n,0,',','.'); }
function angka($n){ return number_format((float)$n,0,',','.'); }
function tgl_id($d){ if(!$d) return '-'; return date('d M Y', strtotime((string)$d)); }
function periode_now(){ return date('Y-m'); }
function bulan_label($periode){
  $b=['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
  [$y,$m]=explode('-',$periode); return ($b[$m]??$m).' '.$y;
}

/* ---------- terbilang ---------- */
function terbilang($n){
  $n=(int)round($n); if($n<0) return 'minus '.terbilang(-$n);
  $s=['','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan','sepuluh','sebelas'];
  if($n<12) return $s[$n];
  elseif($n<20) return terbilang($n-10).' belas';
  elseif($n<100) return terbilang(intdiv($n,10)).' puluh'.($n%10?' '.terbilang($n%10):'');
  elseif($n<200) return 'seratus'.($n-100?' '.terbilang($n-100):'');
  elseif($n<1000) return terbilang(intdiv($n,100)).' ratus'.($n%100?' '.terbilang($n%100):'');
  elseif($n<2000) return 'seribu'.($n-1000?' '.terbilang($n-1000):'');
  elseif($n<1000000) return terbilang(intdiv($n,1000)).' ribu'.($n%1000?' '.terbilang($n%1000):'');
  elseif($n<1000000000) return terbilang(intdiv($n,1000000)).' juta'.($n%1000000?' '.terbilang($n%1000000):'');
  else return terbilang(intdiv($n,1000000000)).' miliar'.($n%1000000000?' '.terbilang($n%1000000000):'');
}
function terbilang_rupiah($n){ $t=trim(terbilang($n)); return ($t?ucfirst($t):'nol').' rupiah'; }

/* ---------- pengaturan ---------- */
function setting($k,$def=null){
  static $c=null;
  if($c===null){ try{ $c=Pengaturan::pluck('v','k')->toArray(); }catch(\Throwable $e){ $c=[]; } }
  return $c[$k] ?? $def;
}
function set_setting($k,$v){ Pengaturan::updateOrCreate(['k'=>$k],['v'=>$v]); }
function nama_koperasi(){ return setting('nama_koperasi','Koperasi'); }

/* ---------- auth (session sederhana) ---------- */
function cu(){ return session('kop_user'); }
function is_admin(){ $u=cu(); return $u && ($u['role']??'')==='admin'; }
function is_member(){ $u=cu(); return $u && ($u['role']??'')==='member'; }
function dalam_jendela_bayar(){ $t=(int)date('j'); return $t>=(int)setting('jatuh_tempo_awal',5) && $t<=(int)setting('jatuh_tempo_akhir',10); }
function icon($n){ return \App\Support\Icons::get($n); }

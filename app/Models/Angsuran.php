<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Angsuran extends Model {
  protected $table='angsuran';
  protected $fillable=['pinjaman_id','tgl','jumlah','pokok_bagian','jasa_bagian','keterangan'];
  protected $casts=['tgl'=>'date','jumlah'=>'float','pokok_bagian'=>'float','jasa_bagian'=>'float'];
  public function pinjaman(){ return $this->belongsTo(Pinjaman::class); }
}

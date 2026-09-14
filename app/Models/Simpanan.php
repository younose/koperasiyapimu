<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Simpanan extends Model {
  protected $table='simpanan';
  protected $fillable=['anggota_id','tgl','jenis','arah','jumlah','catatan'];
  protected $casts=['tgl'=>'date','jumlah'=>'float'];
  public function anggota(){ return $this->belongsTo(Anggota::class); }

  public static function totalJenis($j){
    return (float)static::where('jenis',$j)
      ->selectRaw("COALESCE(SUM(CASE WHEN arah='tarik' THEN -jumlah ELSE jumlah END),0) s")->value('s');
  }
  public static function totalAll(){ return static::totalJenis('Pokok')+static::totalJenis('Wajib')+static::totalJenis('Sukarela'); }
}

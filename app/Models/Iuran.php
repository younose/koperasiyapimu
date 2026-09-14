<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Iuran extends Model {
  protected $table='iuran';
  protected $fillable=['anggota_id','periode','nominal','status','tgl_bayar','simpanan_id'];
  protected $casts=['tgl_bayar'=>'date','nominal'=>'float'];
  public function anggota(){ return $this->belongsTo(Anggota::class); }

  public static function ensureCurrentAll(){
    $per=periode_now(); $nom=(float)setting('iuran_nominal',25000);
    foreach(Anggota::where('aktif',1)->pluck('id') as $id){
      static::firstOrCreate(['anggota_id'=>$id,'periode'=>$per],['nominal'=>$nom,'status'=>'belum']);
    }
  }
  public static function nunggakCount(){
    return (int)static::where('status','belum')->where('periode','<=',periode_now())->distinct()->count('anggota_id');
  }
}

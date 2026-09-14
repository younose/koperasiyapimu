<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pinjaman extends Model {
  protected $table='pinjaman';
  protected $fillable=['anggota_id','tgl_ajukan','tgl_cair','jumlah_pokok','tenor','jasa_persen','status','keterangan'];
  protected $casts=['tgl_ajukan'=>'date','tgl_cair'=>'date','jumlah_pokok'=>'float','jasa_persen'=>'float','tenor'=>'int'];
  public function anggota(){ return $this->belongsTo(Anggota::class); }
  public function angsuran(){ return $this->hasMany(Angsuran::class); }

  public function terbayarPokok(){ return (float)$this->angsuran()->sum('pokok_bagian'); }
  public function sisa(){ return max(0,(float)$this->jumlah_pokok-$this->terbayarPokok()); }
  public function angsuranBulanan(){
    $p=round($this->jumlah_pokok/max(1,$this->tenor));
    $j=round($this->jumlah_pokok*$this->jasa_persen/100);
    return ['pokok'=>$p,'jasa'=>$j,'total'=>$p+$j];
  }
}

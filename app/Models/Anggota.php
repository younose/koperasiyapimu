<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Anggota extends Model {
  protected $table='anggota';
  protected $fillable=['no_anggota','nama','telepon','alamat','pin','tgl_gabung','aktif'];
  protected $casts=['tgl_gabung'=>'date','aktif'=>'boolean'];

  public function simpanan(){ return $this->hasMany(Simpanan::class); }
  public function pinjaman(){ return $this->hasMany(Pinjaman::class); }
  public function iuran(){ return $this->hasMany(Iuran::class); }

  public function saldoJenis($j){
    return (float)$this->simpanan()->where('jenis',$j)
      ->selectRaw("COALESCE(SUM(CASE WHEN arah='tarik' THEN -jumlah ELSE jumlah END),0) s")->value('s');
  }
  public function saldoSukarela(){ return $this->saldoJenis('Sukarela'); }
  public function totalSimpanan(){
    return (float)$this->simpanan()
      ->selectRaw("COALESCE(SUM(CASE WHEN arah='tarik' THEN -jumlah ELSE jumlah END),0) s")->value('s');
  }
  public function outstanding(){
    return (float)$this->pinjaman()->where('status','aktif')->get()->sum(fn($p)=>$p->sisa());
  }
  public function plafon(){ return $this->saldoSukarela()*(float)setting('faktor_plafon',2); }
  public function sisaPlafon(){ return max(0,$this->plafon()-$this->outstanding()); }
  public function iuranBelum(){
    return $this->iuran()->where('status','belum')->where('periode','<=',periode_now())->orderBy('periode')->get();
  }
}

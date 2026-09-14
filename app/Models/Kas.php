<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Kas extends Model {
  protected $table='kas';
  protected $fillable=['tgl','arah','kategori','jumlah','keterangan'];
  protected $casts=['tgl'=>'date','jumlah'=>'float'];
}

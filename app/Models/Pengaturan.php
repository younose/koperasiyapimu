<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pengaturan extends Model {
  protected $table='pengaturan';
  protected $primaryKey='k'; public $incrementing=false; protected $keyType='string';
  public $timestamps=false;
  protected $fillable=['k','v'];
}

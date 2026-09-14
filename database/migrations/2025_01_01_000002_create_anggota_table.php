<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('anggota', function (Blueprint $t) {
      $t->id();
      $t->string('no_anggota',30)->unique();
      $t->string('nama',100);
      $t->string('telepon',30)->nullable();
      $t->string('alamat',200)->nullable();
      $t->string('pin',30)->default('1234');
      $t->date('tgl_gabung');
      $t->boolean('aktif')->default(true);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('anggota'); }
};

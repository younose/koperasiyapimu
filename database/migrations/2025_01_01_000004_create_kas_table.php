<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('kas', function (Blueprint $t) {
      $t->id();
      $t->date('tgl');
      $t->enum('arah',['masuk','keluar']);
      $t->string('kategori',60);
      $t->decimal('jumlah',14,2);
      $t->string('keterangan',200)->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('kas'); }
};

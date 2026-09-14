<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('simpanan', function (Blueprint $t) {
      $t->id();
      $t->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
      $t->date('tgl');
      $t->enum('jenis',['Pokok','Wajib','Sukarela']);
      $t->enum('arah',['setor','tarik'])->default('setor');
      $t->decimal('jumlah',14,2);
      $t->string('catatan',200)->nullable();
      $t->timestamps();
      $t->index('jenis');
    });
  }
  public function down(): void { Schema::dropIfExists('simpanan'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('angsuran', function (Blueprint $t) {
      $t->id();
      $t->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
      $t->date('tgl');
      $t->decimal('jumlah',14,2);
      $t->decimal('pokok_bagian',14,2);
      $t->decimal('jasa_bagian',14,2);
      $t->string('keterangan',200)->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('angsuran'); }
};

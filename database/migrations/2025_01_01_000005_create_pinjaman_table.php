<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('pinjaman', function (Blueprint $t) {
      $t->id();
      $t->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
      $t->date('tgl_ajukan');
      $t->date('tgl_cair')->nullable();
      $t->decimal('jumlah_pokok',14,2);
      $t->integer('tenor')->default(6);
      $t->decimal('jasa_persen',5,2)->default(1.00);
      $t->enum('status',['diajukan','aktif','lunas','ditolak'])->default('diajukan');
      $t->string('keterangan',200)->nullable();
      $t->timestamps();
      $t->index('status');
    });
  }
  public function down(): void { Schema::dropIfExists('pinjaman'); }
};

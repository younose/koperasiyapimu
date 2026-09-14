<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('iuran', function (Blueprint $t) {
      $t->id();
      $t->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
      $t->char('periode',7);
      $t->decimal('nominal',14,2);
      $t->enum('status',['belum','lunas'])->default('belum');
      $t->date('tgl_bayar')->nullable();
      $t->unsignedBigInteger('simpanan_id')->nullable();
      $t->timestamps();
      $t->unique(['anggota_id','periode']);
      $t->index('status');
    });
  }
  public function down(): void { Schema::dropIfExists('iuran'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('admin', function (Blueprint $t) {
      $t->id();
      $t->string('username',50)->unique();
      $t->string('nama',100);
      $t->string('pass_hash');
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('admin'); }
};

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,DashboardController,AnggotaController,SimpananController,KasController,
  PinjamanController,IuranController,ShuController,PengaturanController,LaporanController,CetakController};
use App\Http\Controllers\Member\{PortalController,PinjamanSayaController,AkunController};

Route::get('/', [AuthController::class,'root']);
Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('admin')->group(function () {
  Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

  Route::get('/anggota', [AnggotaController::class,'index'])->name('anggota.index');
  Route::post('/anggota', [AnggotaController::class,'store'])->name('anggota.store');
  Route::post('/anggota/{anggota}/update', [AnggotaController::class,'update'])->name('anggota.update');
  Route::post('/anggota/{anggota}/hapus', [AnggotaController::class,'destroy'])->name('anggota.destroy');

  Route::get('/simpanan', [SimpananController::class,'index'])->name('simpanan.index');
  Route::post('/simpanan', [SimpananController::class,'store'])->name('simpanan.store');
  Route::post('/simpanan/{simpanan}/hapus', [SimpananController::class,'destroy'])->name('simpanan.destroy');

  Route::get('/kas', [KasController::class,'index'])->name('kas.index');
  Route::post('/kas', [KasController::class,'store'])->name('kas.store');
  Route::post('/kas/{kas}/hapus', [KasController::class,'destroy'])->name('kas.destroy');

  Route::get('/pinjaman', [PinjamanController::class,'index'])->name('pinjaman.index');
  Route::get('/pinjaman/{pinjaman}', [PinjamanController::class,'show'])->name('pinjaman.show');
  Route::post('/pinjaman', [PinjamanController::class,'store'])->name('pinjaman.store');
  Route::post('/pinjaman/{pinjaman}/setuju', [PinjamanController::class,'approve'])->name('pinjaman.approve');
  Route::post('/pinjaman/{pinjaman}/tolak', [PinjamanController::class,'reject'])->name('pinjaman.reject');
  Route::post('/pinjaman/{pinjaman}/bayar', [PinjamanController::class,'bayar'])->name('pinjaman.bayar');
  Route::post('/pinjaman/{pinjaman}/lunasi', [PinjamanController::class,'lunasi'])->name('pinjaman.lunasi');

  Route::get('/iuran', [IuranController::class,'index'])->name('iuran.index');
  Route::post('/iuran/generate', [IuranController::class,'generate'])->name('iuran.generate');
  Route::post('/iuran/backfill', [IuranController::class,'backfill'])->name('iuran.backfill');
  Route::post('/iuran/bayar', [IuranController::class,'bayar'])->name('iuran.bayar');

  Route::get('/shu', [ShuController::class,'index'])->name('shu.index');
  Route::post('/shu/parameter', [ShuController::class,'updateSetting'])->name('shu.setting');
  Route::post('/shu/bagikan', [ShuController::class,'bagikan'])->name('shu.bagikan');

  Route::get('/pengaturan', [PengaturanController::class,'index'])->name('pengaturan.index');
  Route::post('/pengaturan/umum', [PengaturanController::class,'updateUmum'])->name('pengaturan.umum');
  Route::post('/pengaturan/password', [PengaturanController::class,'updatePassword'])->name('pengaturan.password');

  Route::get('/laporan', [LaporanController::class,'index'])->name('laporan.index');
});

Route::middleware('member')->group(function () {
  Route::get('/portal', [PortalController::class,'index'])->name('portal');
  Route::get('/pinjaman-saya', [PinjamanSayaController::class,'index'])->name('pinjaman-saya');
  Route::post('/pinjaman-saya/ajukan', [PinjamanSayaController::class,'ajukan'])->name('pinjaman-saya.ajukan');
  Route::get('/akun', [AkunController::class,'index'])->name('akun');
  Route::post('/akun/pin', [AkunController::class,'updatePin'])->name('akun.pin');
});

Route::middleware('kauth')->group(function () {
  Route::get('/cetak/kartu/{anggota}', [CetakController::class,'kartu'])->name('cetak.kartu');
  Route::get('/cetak/bukti/{simpanan}', [CetakController::class,'bukti'])->name('cetak.bukti');
});

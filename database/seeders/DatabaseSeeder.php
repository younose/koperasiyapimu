<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Admin,Anggota,Simpanan,Kas,Pengaturan};
class DatabaseSeeder extends Seeder {
  public function run(): void {
    Admin::updateOrCreate(['username'=>'admin'],
      ['nama'=>'Pengurus Koperasi','pass_hash'=>Hash::make('admin123')]);

    $set=[
      'nama_koperasi'=>'Koperasi Konsumen Yapimu Ahsanu Amala',
      'iuran_nominal'=>'25000','jatuh_tempo_awal'=>'5','jatuh_tempo_akhir'=>'10',
      'faktor_plafon'=>'2','jasa_pinjaman'=>'1.0','shu_pct_modal'=>'25','shu_pct_usaha'=>'40',
    ];
    foreach($set as $k=>$v) Pengaturan::updateOrCreate(['k'=>$k],['v'=>$v]);

    if(Anggota::count()===0){
      $today=date('Y-m-d');
      $data=[
        ['AGT-001','Siti Rahayu','0812-1111-2222','Jl. Melati No. 4','1001'],
        ['AGT-002','Budi Santoso','0813-3333-4444','Jl. Kenanga No. 12','1002'],
        ['AGT-003','Dewi Lestari','0857-5555-6666','Jl. Anggrek No. 7','1003'],
        ['AGT-004','Ahmad Fauzi','0821-7777-8888','Jl. Cempaka No. 21','1004'],
        ['AGT-005','Rina Wijaya','0812-9999-0000','Jl. Dahlia No. 3','1005'],
      ];
      foreach($data as $d){
        $a=Anggota::create(['no_anggota'=>$d[0],'nama'=>$d[1],'telepon'=>$d[2],'alamat'=>$d[3],'pin'=>$d[4],'tgl_gabung'=>$today,'aktif'=>1]);
        Simpanan::create(['anggota_id'=>$a->id,'tgl'=>$today,'jenis'=>'Pokok','arah'=>'setor','jumlah'=>100000,'catatan'=>'Simpanan pokok saat mendaftar']);
        Simpanan::create(['anggota_id'=>$a->id,'tgl'=>$today,'jenis'=>'Wajib','arah'=>'setor','jumlah'=>25000,'catatan'=>'Iuran wajib awal']);
      }
      Simpanan::create(['anggota_id'=>1,'tgl'=>$today,'jenis'=>'Sukarela','arah'=>'setor','jumlah'=>500000,'catatan'=>'Setoran sukarela']);
      Simpanan::create(['anggota_id'=>2,'tgl'=>$today,'jenis'=>'Sukarela','arah'=>'setor','jumlah'=>300000,'catatan'=>'Setoran sukarela']);
      Simpanan::create(['anggota_id'=>3,'tgl'=>$today,'jenis'=>'Sukarela','arah'=>'setor','jumlah'=>250000,'catatan'=>'Setoran sukarela']);
      Kas::create(['tgl'=>$today,'arah'=>'masuk','kategori'=>'Bunga Bank','jumlah'=>45000,'keterangan'=>'Bunga rekening koperasi']);
      Kas::create(['tgl'=>$today,'arah'=>'keluar','kategori'=>'ATK','jumlah'=>60000,'keterangan'=>'Pembelian buku kas & alat tulis']);
    }
  }
}

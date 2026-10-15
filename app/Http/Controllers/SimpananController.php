<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function index(Request $r)
    {
        $anggotaId = $r->anggota_id;
        $dari = $r->dari;
        $sampai = $r->sampai;
        $q = Simpanan::with('anggota')->orderByDesc('tgl')->orderByDesc('id');
        if ($anggotaId) {
            $q->where('anggota_id', $anggotaId);
        }
        if ($dari) {
            $q->whereDate('tgl', '>=', $dari);
        }
        if ($sampai) {
            $q->whereDate('tgl', '<=', $sampai);
        }
        $filtered = $anggotaId || $dari || $sampai;

        return view('admin.simpanan', [
            'title' => 'Simpanan',
            'members' => Anggota::where('aktif', 1)->orderBy('no_anggota')->get(),
            'hist' => $q->limit($filtered ? 300 : 40)->get(),
            'anggotaId' => $anggotaId,
            'dari' => $dari,
            'sampai' => $sampai,
            'filtered' => $filtered,
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['anggota_id' => 'required|exists:anggota,id', 'jenis' => 'required|in:Pokok,Wajib,Sukarela', 'arah' => 'required|in:setor,tarik', 'jumlah' => 'required|numeric|min:1', 'tgl' => 'nullable|date', 'catatan' => 'nullable']);
        Simpanan::create(['anggota_id' => $d['anggota_id'], 'tgl' => $r->tgl ?: date('Y-m-d'), 'jenis' => $d['jenis'], 'arah' => $d['arah'], 'jumlah' => $d['jumlah'], 'catatan' => $r->catatan]);

        return redirect()->route('simpanan.index')->with('ok', 'Transaksi simpanan dicatat & masuk ke buku kas.');
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();

        return redirect()->route('simpanan.index')->with('ok', 'Catatan simpanan dihapus.');
    }
}

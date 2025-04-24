<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Tamu;
use App\Models\Presensi;
use App\Models\Kunjungan;
use App\Models\TamuAcara;
use Carbon\Carbon;

class QRCodeController extends Controller
{
    public function checkQRCode($code)
    {
        // Validasi kode QR
        if (!$code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode QR tidak valid.'
            ], 400);
        }

        // Decode URL untuk memastikan format terbaca
        $code = urldecode($code);

        // Decode JSON dari QR Code
        $data = json_decode($code, true);

        if (!$data || !isset($data['id_tamu']) || !isset($data['kode_acara'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid, ID Tamu atau Kode Acara tidak ditemukan.',
            ], 400);
        }

        $idTamu = $data['id_tamu'];
        $acara = $data['kode_acara'];
        $nip = null;

        // Jika id_tamu adalah ID Pegawai (karyawan)
        if (is_numeric($idTamu)) {
            $nip = $idTamu; // Anggap ID Tamu adalah NIP karyawan
        } else {
            $idTamu = null; // Jika tidak, maka id_tamu adalah untuk tamu
        }

        // Jika QR Code berisi NIP (Karyawan)
        if ($nip) {
            $karyawan = Karyawan::where('nip', $nip)->first();
            if ($karyawan) {
                // Log presensi karyawan
                if (!Presensi::where('nip', $nip)->where('kode_acara', $acara)->exists()) {
                    Presensi::create([
                        'nip' => $karyawan->nip,
                        'kode_acara' => $acara,
                        'jam_masuk' => Carbon::now(),
                        'tanggal' => Carbon::now()->toDateString(),
                    ]);
                }

                return response()->json([
                    'status' => 'karyawan',
                    'nama' => $karyawan->nama,
                    'kode_acara' => $acara,
                ]);
            }
        }

        // Jika QR Code berisi ID Tamu
        if ($idTamu) {
            $tamu = Tamu::where('id_tamu', $idTamu)->first();
            if ($tamu) {
                // Log kunjungan tamu
                if (!Kunjungan::where('id_tamu', $idTamu)->where('kode_acara', $acara)->exists()) {
                    Kunjungan::create([
                        'id_tamu' => $idTamu,
                        'kode_acara' => $acara,
                        'jam_masuk' => Carbon::now(),
                        'tanggal' => Carbon::now()->toDateString(),
                    ]);
                }

                // Update status tamu di tabel tamu_acaras menjadi 'hadir'
                TamuAcara::where('id_tamu', $idTamu)
                    ->where('kode_acara', $acara)
                    ->update(['status' => 'hadir']);

                return response()->json([
                    'status' => 'tamu',
                    'nama' => $tamu->nama,
                    'kode_acara' => $acara,
                ]);
            }
        }

        // Jika data tidak ditemukan
        return response()->json([
            'status' => 'not_found',
            'message' => 'QR Code tidak ditemukan.',
        ], 404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleQRCode;
use Illuminate\Support\Facades\Response;

class GenerateQRController extends Controller
{
    // Method untuk menampilkan form
    public function showForm()
    {
        return view('admin.generateqr.generate'); // Gantilah dengan view yang sesuai
    }

    // Method untuk menghasilkan QR Code
    public function generate(Request $request)
    {
        // Validasi input berdasarkan tipe (Pegawai atau Tamu)
        $request->validate([
            'type' => 'required|in:pegawai,tamu',
            'input1' => 'required|string',
            'kode_acara' => 'required|string',
        ]);

        // Ambil data dari input
        $type = $request->input('type');
        $input1 = $request->input('input1');
        $kodeAcara = $request->input('kode_acara');

        // Format data berdasarkan tipe
        $data = $type === 'pegawai'
            ? "NIP: $input1, Kode Acara: $kodeAcara"
            : "ID Tamu: $input1, Kode Acara: $kodeAcara";

        // Menghasilkan QR Code
        $qrCode = \SimpleQRCode::generate($data);

        // Kembalikan QR Code dalam format gambar
        return response($qrCode)->header('Content-Type', 'image/png');
    }
}

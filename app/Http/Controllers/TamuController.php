<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TamuController extends Controller
{
    public function datatamu()
    {
        // Ambil semua data tamu
        $tamus = Tamu::all();

        // Tampilkan view datatamu dan kirim data tamu
        return view('admin.tamu.datatamu', compact('tamus'));
    }

    public function create()
    {
        // Tampilkan form untuk menambahkan tamu baru
        return view('admin.tamu.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_tamu' => 'required|unique:tamus',
            'nama' => 'required|string|max:255',
            'asal_instansi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        // Simpan data tamu
        Tamu::create($request->all());

        // Redirect ke halaman daftar tamu dengan pesan sukses
        return redirect()->route('tamu.datatamu')->with('success', 'Data tamu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Ambil data tamu berdasarkan ID
        $decryptID = Crypt::decryptString($id);
        $tamu = Tamu::findOrFail($decryptID);

        // Tampilkan form edit tamu
        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_tamu' => 'required|unique:tamus,id_tamu,' . $id,
            'nama' => 'required|string|max:255',
            'asal_instansi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        // Update data tamu
        $tamu = Tamu::findOrFail($id);
        $tamu->update($request->all());

        // Redirect ke halaman daftar tamu dengan pesan sukses
        return redirect()->route('tamu.datatamu')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Hapus data tamu
        $tamu = Tamu::findOrFail($id);
        $tamu->delete();

        // Redirect ke halaman daftar tamu dengan pesan sukses
        return redirect()->route('tamu.datatamu')->with('success', 'Data tamu berhasil dihapus.');
    }

    public function generateId()
    {
        // Ambil ID terakhir dari tabel tamu
        $lastTamu = Tamu::latest('id_tamu')->first();

        if ($lastTamu) {
            // Ambil nomor ID terakhir dan tambahkan 1
            $lastIdNumber = (int)substr($lastTamu->id_tamu, -4);  // Ambil 5 digit terakhir dari ID tamu
            $newId = '' . str_pad($lastIdNumber + 1, 4, STR_PAD_LEFT); // Format ID baru
        } else {
            // Jika tidak ada data sebelumnya, mulai dengan ID pertama
            $newId = 'TAMU-00001';
        }

        return response()->json(['id_tamu' => $newId]);
    }
}

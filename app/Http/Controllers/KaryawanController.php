<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Crypt;

class KaryawanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Dashboard
    public function dashboard()
    {
        $karyawans = Karyawan::all();
        return view('admin.dashboard', compact('karyawans'));
    }

    // Profil Karyawan
    public function profile()
    {
        $user = auth()->user();
        $karyawan = $user->karyawan;

        return view('admin.karyawan.profile', compact('karyawan', 'user'));
    }

    // Daftar Data Karyawan
    public function datakar()
    {
        $karyawans = Karyawan::all();
        return view('admin.karyawan.datakar', compact('karyawans'));
    }

    // Tampilkan Form Tambah Karyawan
    public function create()
    {
        return view('admin.karyawan.create');
    }

    // Simpan Data Karyawan Baru
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:karyawans,nip|string|max:20',
            'nama' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.datakar')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    // Tampilkan Form Edit Karyawan
    public function edit($id)
    {
        $decryptID = Crypt::decryptString($id);
        $karyawan = Karyawan::findOrFail($decryptID);
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    // Update Data Karyawan
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nip' => 'required|string|max:20|unique:karyawans,nip,' . $id,
            'nama' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($validatedData);

        return redirect()->route('karyawan.datakar')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // Hapus Data Karyawan
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('karyawan.datakar')->with('success', 'Data karyawan berhasil dihapus.');
    }
}

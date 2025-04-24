<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya admin yang bisa mengakses
    }

    // Metode untuk menampilkan data pengguna
    public function datauser()
    {
        $users = User::with('karyawan')->get();
        return view('admin.admin.datauser', compact('users')); // Mengarah ke admin/admin/datauser
    }

    // Menampilkan daftar admin
    public function index()
    {
        // Ambil semua data admin
        $admins = User::where('role', 'admin')->get();

        // Tampilkan daftar admin
        return view('admin.admin.datauser', compact('users')); // Mengarah ke admin/admin/datauser
    }

    // Menampilkan form tambah admin
    public function create()
    {
        // Ambil semua karyawan untuk memilih NIP
        $karyawans = Karyawan::whereDoesntHave('user')->get();


        // Tampilkan form tambah admin
        return view('admin.admin.create', compact('karyawans')); // Mengarah ke admin/admin/create
    }

    // Menyimpan data admin
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,karyawan', // Pastikan role yang diterima adalah 'admin'
            'nip' => 'required|exists:karyawans,nip',
            'password' => 'required|min:8|confirmed',
        ]);

        // Simpan data ke tabel users
        User::create([
            'email' => $request->email,
            'role' => $request->role, // Tetapkan 'admin' meski pengguna mencoba mengirim role lain
            'nip' => $request->nip,
            'password' => bcrypt($request->password),
        ]);

        // Redirect ke halaman data user dengan pesan sukses
        return redirect()->route('admin.datauser')->with('success', 'Admin berhasil ditambahkan.');
    }

    // Menghapus data user
    public function destroy($id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus user
        $user->delete();

        // Redirect ke halaman datauser dengan pesan sukses
        return redirect()->route('admin.datauser')->with('success', 'User berhasil dihapus.');
    }
}

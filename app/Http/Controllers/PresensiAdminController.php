<?php
namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Kunjungan;
use App\Models\Tamu;
use App\Models\Event;

class PresensiAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchPresensi = $request->input('search_presensi');
        $searchKunjungan = $request->input('search_kunjungan');
        
        // Ambil data presensi dengan pencarian berdasarkan kode acara (jika ada)
        if ($search) {
            $presensi = Presensi::with(['event', 'kunjungan'])
                ->whereHas('event', function ($query) use ($search) {
                    $query->where('kode_acara', 'like', "%$search%");
                })
                ->get();
        } else {
            // Ambil semua data presensi tanpa filter
            $presensi = Presensi::with(['event', 'kunjungan'])->get();
        }

        // Ambil data kunjungan dengan relasi tamu
        $kunjungan = Kunjungan::with('tamu') // Pastikan ada relasi di model Kunjungan
                                ->where('kunjungans.id','>=','1')
                                ->join('tamus', 'tamus.id_tamu', '=', 'kunjungans.id_tamu')
                                ->get();
        
        $event = Event::all(); // Ambil semua data acara
        // Kirim semua data ke view
        return view('admin.presensis.index', compact('presensi', 'kunjungan', 'event'));
    }

    public function getPresensiAndKunjunganByAcara($kode_acara)
    {
        // Mengambil data presensi dan kunjungan berdasarkan kode acara
        $presensi = Presensi::where('kode_acara', $kode_acara)->get();
        $kunjungan = Kunjungan::where('kode_acara', $kode_acara)->get();
        
        // Mengubah data presensi dan kunjungan ke format yang sesuai untuk respons JSON
        $presensiData = $presensi->map(function($item) {
            return [
                'id' => $item->id,
                'kode_acara' => $item->kode_acara,
                'nip' => $item->nip,
                'nama' => $item->karyawan->nama,
                'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'),
                'jam_masuk' => \Carbon\Carbon::parse($item->jam_masuk)->format('H:i'),
                'jabatan' => $item->karyawan->jabatan,
                'status' => $item->status,
            ];
        });

        $kunjunganData = $kunjungan->map(function($item) {
            return [
                'no' => $item->id, // Bisa disesuaikan dengan data yang Anda inginkan
                'kode_acara' => $item->kode_acara,
                'id_tamu' => $item->id_tamu,
                'nama_tamu' => $item->tamu->nama, // Pastikan relasi di model Kunjungan sudah ada
                'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'),
                'jam_masuk' => \Carbon\Carbon::parse($item->jam_masuk)->format('H:i'),
                'asal_instansi' => $item->tamu->asal_instansi,
                'jabatan' => $item->tamu->jabatan,
            ];
        });

        // Mengembalikan data dalam format JSON
        return response()->json([
            'presensi' => $presensiData,
            'kunjungan' => $kunjunganData
        ]);
    }

    public function downloadKunjunganPdf(Request $request)
    {
        $kode_acara = $request->input('kode_acara');

        $event = Event::where('kode_acara', $kode_acara)->first();
        if (!$event) {
            abort(404, 'Acara tidak ditemukan');
        }
    
        $kunjungan = Kunjungan::where('kode_acara', $kode_acara)->get();
    
        $pdf = Pdf::loadView('pdf.kunjungan', compact('event', 'kunjungan'));
    
        return $pdf->download('kunjungan_' . $event->nama_acara . '.pdf');
    }

    public function getEventDetails($kode_acara)
    {
        $event = Event::where('kode_acara', $kode_acara)->first();

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $presensi = $event->presensi; // Pastikan relasi ada di model Event
        $kunjungan = $event->kunjungan; // Pastikan relasi ada di model Event

        return response()->json([
            'event' => $event,
            'presensi' => $presensi,
            'kunjungan' => $kunjungan,
        ]);
    }



}

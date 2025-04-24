<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TamuAcara;
use App\Models\Tamu;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Crypt;

class AcaraController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('tanggal', '>=', now())
                                ->orderBy('tanggal', 'asc')
                                ->take(10)
                                ->get();

        return view('admin.acara.acara', compact('upcomingEvents'));
    }

    public function getSchedules()
    {
        $events = Event::all();
        $schedules = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'kode_acara' => $event->kode_acara,
                'title' => $event->nama_acara,
                'start' => $event->tanggal . 'T' . $event->jam_mulai,
                'end' => $event->tanggal . 'T' . $event->jam_selesai,
                'tempat' => $event->tempat,
                'backgroundColor' => '#007bff',
                'borderColor' => '#0056b3',
            ];
        });

        return response()->json($schedules);
    }

    public function showAddEventForm(Request $request)
    {
        $date = $request->query('date');
        return view('admin.acara.tambah_acara', compact('date'));
    }

    public function storeEvent(Request $request)
    {
        $validatedData = $request->validate([
            'kode_acara' => 'required|string|max:255|unique:events,kode_acara',
            'nama_acara' => 'required|string|max:255',
            'event_date' => 'required|date',
            'tempat' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Event::create([
            'kode_acara' => $validatedData['kode_acara'],
            'nama_acara' => $validatedData['nama_acara'],
            'tanggal' => $validatedData['event_date'],
            'tempat' => $validatedData['tempat'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
        ]);

        return redirect()->route('acara')->with('notification', [
            'type' => 'success',
            'message' => 'Acara berhasil ditambahkan!'
        ]);
    }

    public function editEvent($id)
    {
        $decryptID = Crypt::decryptString($id);
        $event = Event::findOrFail($decryptID);
        return view('admin.acara.edit', compact('event'));
    }

    // app/Http/Controllers/EventController.php

    public function getLastKodeAcara(Request $request)
    {
        $date = $request->query('date'); // Ambil tanggal dari query string

        // Pastikan format tanggal sesuai dengan yang diharapkan (YYYYMMDD)
        if ($date) {
            $lastEvent = Event::whereDate('event_date', $date)
                            ->orderBy('kode_acara', 'desc')
                            ->first();

            if ($lastEvent) {
                return response()->json([
                    'success' => true,
                    'lastKodeAcara' => $lastEvent->kode_acara,
                ]);
            }

            // Jika tidak ada data, kembalikan default
            return response()->json([
                'success' => true,
                'lastKodeAcara' => null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tanggal tidak valid',
        ]);
    }
    
    public function updateEvent(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_acara' => 'required|string|max:255',
            'event_date' => 'required|date',
            'tempat' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $event = Event::findOrFail($id);
        $event->update([
            'nama_acara' => $validatedData['nama_acara'],
            'tanggal' => $validatedData['event_date'],
            'tempat' => $validatedData['tempat'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
        ]);

        return redirect()->route('acara.detail', $event->id)->with('notification', [
            'type' => 'success',
            'message' => 'Acara berhasil diperbarui!'
        ]);
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('acara')->with('notification', [
            'type' => 'success',
            'message' => 'Acara berhasil dihapus!'
        ]);
    }

    public function showEventDetail($id)
    {
        $event = Event::findOrFail($id);
        
        $availableGuests = Tamu::whereNotIn('id_tamu', function($query) use ($event) {
            $query->select('id_tamu')
                  ->from('tamu_acaras')
                  ->where('kode_acara', $event->kode_acara);
        })->get();

        $tamus = TamuAcara::where('kode_acara', $event->kode_acara)
            ->with('tamu')
            ->get();

        // Generate QR Code data for each guest
        foreach ($tamus as $tamuAcara) {
            $qrData = [
                'id_tamu' => $tamuAcara->id_tamu,
                'kode_acara' => $event->kode_acara,
            ];
            
            $tamuAcara->qrCode = QrCode::size(200)->generate(json_encode($qrData));
        }

        return view('admin.acara.detail_acara', compact('event', 'tamus', 'availableGuests'));
    }

    public function showAddGuestForm($id)
    {
        $decryptID = Crypt::decryptString($id);
        $event = Event::findOrFail($decryptID);
        
        // Get all guests who are not yet invited to this event
        $availableGuests = Tamu::whereNotIn('id_tamu', function($query) use ($event) {
            $query->select('id_tamu')
                  ->from('tamu_acaras')
                  ->where('kode_acara', $event->kode_acara);
        })->get();

        return view('admin.acara.tambah_tamu', compact('event', 'availableGuests'));
    }

    public function getGuestData($id)
    {
        $tamu = Tamu::find($id);
        return response()->json($tamu);
    }


    public function removeGuestFromEvent($eventId, $guestId)
    {
        $event = Event::findOrFail($eventId);
        
        $tamuAcara = TamuAcara::where('kode_acara', $event->kode_acara)
            ->where('id_tamu', $guestId)
            ->firstOrFail();

        $tamuAcara->delete();

        return redirect()->route('acara.detail', $eventId)->with('notification', [
            'type' => 'success',
            'message' => 'Tamu berhasil dihapus dari acara!'
        ]);
    }

    public function downloadAllQrCodes($eventId)
    {
        $event = Event::findOrFail($eventId);
        $tamus = TamuAcara::where('kode_acara', $event->kode_acara)
            ->with('tamu')
            ->get();

        // Create temporary directory
        $tempDir = storage_path('app/public/temp/qrcodes-' . Str::random(8));
        File::makeDirectory($tempDir, 0777, true);

        // Generate QR codes for each guest
        foreach ($tamus as $tamuAcara) {
            $qrData = [
                'id_tamu' => $tamuAcara->tamu->id_tamu,
                'kode_acara' => $event->kode_acara,
            ];

            $filename = Str::slug($tamuAcara->tamu->nama) . '-' . $event->kode_acara . '.svg';
            $qrCode = QrCode::size(300)->generate(json_encode($qrData));
            
            File::put($tempDir . '/' . $filename, $qrCode);
        }

        // Create ZIP archive
        $zipFileName = 'qrcodes-' . $event->kode_acara . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipFileName);

        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($tempDir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = basename($filePath);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        // Clean up temporary directory
        File::deleteDirectory($tempDir);

        // Return ZIP file and delete after download
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend();
    }

    // AcaraController.php - method addGuestToEvent
    public function addGuestToEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validatedData = $request->validate([
            'id_tamu' => 'required',
            'nama' => 'required|string|max:255',
            'asal_instansi' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        // Check if guest already exists with different attributes
        $existingTamu = Tamu::where('id_tamu', $validatedData['id_tamu'])->first();

        if ($existingTamu) {
            // Case 1: Guest exists with different attributes
            if ($existingTamu->nama != $validatedData['nama'] ||
                $existingTamu->asal_instansi != $validatedData['asal_instansi'] ||
                $existingTamu->jabatan != $validatedData['jabatan']) {
                
                return back()->with('notification', [
                    'type' => 'danger',
                    'message' => 'ID Tamu ' . $validatedData['id_tamu'] . ' sudah digunakan dengan data yang berbeda, silahkan pilih ID lain.'
                ]);
            }
            
            // Case 2: Guest exists with same attributes
            $guestEvent = TamuAcara::create([
                'id_tamu' => $validatedData['id_tamu'],
                'kode_acara' => $event->kode_acara,
                'status' => 'tidak hadir'
            ]);
        } else {
            // Case 3: New guest
            $tamu = Tamu::create([
                'id_tamu' => $validatedData['id_tamu'],
                'nama' => $validatedData['nama'],
                'asal_instansi' => $validatedData['asal_instansi'],
                'jabatan' => $validatedData['jabatan']
            ]);

            $guestEvent = TamuAcara::create([
                'id_tamu' => $tamu->id_tamu,
                'kode_acara' => $event->kode_acara,
                'status' => 'tidak hadir'
            ]);
        }

        return redirect()->route('acara.detail', $event->id)->with('notification', [
            'type' => 'success',
            'message' => 'Tamu berhasil ditambahkan ke acara.'
        ]);
    }

    public function getGuest($id_tamu)
    {
        $guest = Tamu::find($id_tamu);
        if ($guest) {
            return response()->json([
                'id_tamu' => $guest->id_tamu,
                'nama' => $guest->nama,
                'asal_instansi' => $guest->asal_instansi,
                'jabatan' => $guest->jabatan,
            ]);
        }

        return response()->json(['message' => 'Tamu tidak ditemukan'], 404);
    }
    
    


}
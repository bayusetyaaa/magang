<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Kunjungan;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Check user role
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } else if ($user->role === 'pegawai') {
            return redirect()->route('scanqr');
        }

        // Fallback for undefined roles
        return redirect()->route('login');
    }

    /**
     * Display admin dashboard with statistics.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function adminDashboard()
    {
        // Get start and end dates of current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get total guests this month
        $totalHadir = Kunjungan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();

        // Get total events this month
        $totalAcara = Event::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();

        // Get last completed event
        $lastEvent = Event::where('tanggal', '<=', now())->latest('tanggal')->first();
        
        if (!$lastEvent) {
            $lastEvent = (object)[
                'nama_acara' => 'Tidak ada acara',
                'tanggal' => '-',
                'kode_acara' => null
            ];
            $tamuHadir = 0;
        } else {
            $tamuHadir = Kunjungan::where('kode_acara', $lastEvent->kode_acara)->count();
        }

        // Get last 10 completed events with guest counts
        $last10Events = Event::where('tanggal', '<=', now())
            ->orderBy('tanggal', 'desc')
            ->take(10)
            ->get()
            ->map(function ($event) {
                $event->tamu_hadir = Kunjungan::where('kode_acara', $event->kode_acara)->count();
                return $event;
            });

        // Get upcoming events
        $upcomingEvents = Event::where('tanggal', '>', now())
            ->orderBy('tanggal')
            ->get();

        // Return admin dashboard view with data
        return view('admin.dashboard', compact(
            'lastEvent',
            'totalHadir',
            'tamuHadir',
            'totalAcara',
            'last10Events',
            'upcomingEvents'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifikasi()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markAsRead(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            abort(403);
        }

        $notifikasi->update(['dibaca' => true]);

        if ($notifikasi->link) {
            return redirect($notifikasi->link);
        }

        return redirect()->route('notifikasi.index');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifikasi()
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return redirect()->route('notifikasi.index')
            ->with('success', 'Semua notifikasi telah dibaca.');
    }
}

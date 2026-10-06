<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function store(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'tipe' => 'required|in:foto,dokumen,notulen',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('dokumentasi/' . $kegiatan->id, 'public');
            Dokumentasi::create([
                'kegiatan_id' => $kegiatan->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'tipe' => $request->tipe,
            ]);
        }

        return redirect()->route('kegiatan.show', $kegiatan)
            ->with('success', 'Dokumentasi berhasil diupload.');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        Storage::disk('public')->delete($dokumentasi->file_path);
        $kegiatanId = $dokumentasi->kegiatan_id;
        $dokumentasi->delete();

        return redirect()->route('kegiatan.show', $kegiatanId)
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}

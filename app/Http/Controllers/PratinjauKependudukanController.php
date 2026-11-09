<?php
namespace App\Http\Controllers;

use App\Models\LayananDesa;
use Illuminate\Support\Facades\Auth;

class PratinjauKependudukanController extends Controller
{
    public function show(LayananDesa $service)
    {
        abort_unless(Auth::user()?->role === 'warga', 403);
        abort_unless($service->kategori?->nama === 'Administrasi Kependudukan', 404);
        $spec = config('desara_kependudukan')[$service->nama] ?? null;
        abort_unless($spec, 404);
        return view('layanan.preview-kependudukan', compact('service', 'spec'));
    }
}

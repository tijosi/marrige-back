<?php

namespace App\Http\Controllers;

use App\Models\Presentes;
use Illuminate\Support\Facades\File;

class PresentesController extends Controller
{
    public function getPresentes() {
        $presentes = Presentes::all();

        $imageName = 'maquina-lavar-louca-1.png';
        $path = public_path('images/presentes/maquina-lavar-louca/' . $imageName);

        if (File::exists($path)) {

            $mime = File::mimeType($path);

            return response()->json(['Content-Type' => $mime, 'path' => $path]);
        }

        return response()->json($presentes);
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    // VULNERABILITY: Path Traversal — user controls file path
    public function download(Request $request)
    {
        $filename = $request->input('file');
        // Attacker can pass: ../../.env or ../../../etc/passwd
        $path = storage_path('app/public/' . $filename);
        return response()->download($path);
    }

    // VULNERABILITY: Path Traversal — read arbitrary file
    public function previewImage(Request $request)
    {
        $file = $request->input('path');
        $content = file_get_contents(base_path($file)); // No path validation
        return response($content)->header('Content-Type', 'image/jpeg');
    }
}

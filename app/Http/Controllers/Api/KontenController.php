<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KontenIquran;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KontenController extends Controller
{
    public function index($key)
    {
        $konten = KontenIquran::where('nama_key', $key)->first();
        $path   = storage_path('app/public/' . $konten->path_konten);

        if ($konten->jenis == 'video') {
            $response = new StreamedResponse(function () use ($path) {
                $stream = fopen($path, 'rb');
                while (! feof($stream)) {
                    echo fread($stream, 1024 * 8); // Adjust buffer size as needed
                    flush();
                }
                fclose($stream);
            });

            $response->headers->set('Content-Type', 'video/mp4'); // Set the appropriate MIME type for the video format
            $response->headers->set('Content-Length', filesize($path));
            $response->headers->set('Accept-Ranges', 'bytes');

            return $response;
        } else {
            return response()->download($path);
        }

    }
}

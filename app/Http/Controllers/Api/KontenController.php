<?php
namespace App\Http\Controllers\Api;

use App\Models\KontenIquran;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KontenController extends Controller
{
    public function index($key)
    {
        // $konten = KontenIquran::where('nama_key', $key)->first();
        // if ($konten) {
        //     $path = storage_path('app/public/' . $konten->path_konten);

        //     if ($konten->jenis == 'video') {

        //         $response = new StreamedResponse(function () use ($path) {
        //             $stream = fopen($path, 'rb');
        //             $bufferSize = 1024 * 1024; // 1 MB buffer size

        //             ob_start(); // Enable output buffering
        //             while (! feof($stream)) {
        //                 echo fread($stream, $bufferSize);
        //                 ob_flush(); // Flush the output buffer
        //                 flush();
        //             }
        //             ob_end_clean(); // Clean the output buffer

        //             fclose($stream);
        //         });

        //         $response->headers->set('Content-Type', 'video/mp4');
        //         $response->headers->set('Content-Length', filesize($path));
        //         $response->headers->set('Accept-Ranges', 'bytes');

        //         // CORS headers
        //         $response->headers->set('Access-Control-Allow-Origin', '*');
        //         $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
        //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');

        //         return $response;

        //     } else {
        //         return response()->download($path);
        //     }
        // } else {
        //     return response()->json([
        //         'status' => '404',
        //         'message' => 'Content not found !',
        //     ]);
        // }

        $konten = KontenIquran::where('nama_key', $key)->first();

        if (!$konten) {
            return response()->json([
                'status' => '404',
                'message' => 'Content not found!',
            ], 404);
        }

        $path = storage_path('app/public/' . $konten->path_konten);

        if (!file_exists($path)) {
            return response()->json([
                'status' => '404',
                'message' => 'Video file not found!',
            ], 404);
        }

        // Untuk non-video files, tetap pakai download
        if ($konten->jenis != 'video') {
            return response()->download($path);
        }

        return $this->streamVideoFile($path);

    }

    private function streamVideoFile($filePath)
    {
        $fileSize = filesize($filePath);
        $file = fopen($filePath, 'rb');

        // Get range header from request
        $rangeHeader = request()->header('Range');
        $start = 0;
        $end = $fileSize - 1;
        $chunkSize = 1024 * 1024; // 1MB chunks

        // Handle byte range requests
        if ($rangeHeader && preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
            $start = intval($matches[1]);
            $end = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $fileSize - 1;
            $end = min($end, $fileSize - 1);
        }

        $length = $end - $start + 1;

        // Set headers for partial content
        $headers = [
            'Content-Type' => 'video/mp4',
            'Content-Length' => $length,
            'Accept-Ranges' => 'bytes',
            'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
            'Content-Disposition' => 'inline', // Play in browser, don't download
        ];

        // CORS headers
        $corsHeaders = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Range',
            'Access-Control-Expose-Headers' => 'Content-Length, Content-Range',
        ];

        $headers = array_merge($headers, $corsHeaders);

        // If it's a range request, return partial content
        if ($rangeHeader) {
            $status = 206; // Partial Content
        } else {
            $status = 200; // Full Content
        }

        // Stream the video in chunks
        $response = new StreamedResponse(function () use ($file, $start, $length, $chunkSize) {
            fseek($file, $start);

            $bytesToSend = $length;
            while ($bytesToSend > 0 && !feof($file)) {
                $chunk = min($chunkSize, $bytesToSend);
                echo fread($file, $chunk);
                $bytesToSend -= $chunk;

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                // Optional: Add small delay to prevent overwhelming the server
                // usleep(10000); // 10ms delay
            }

            fclose($file);
        }, $status, $headers);

        return $response;
    }
}

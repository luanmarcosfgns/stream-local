<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileServeController extends Controller
{
    private string $basePath = '/mnt/jellyflin/';

    public function serve(Request $request, $path)
    {
        $full = $this->basePath . $path;

        if (!file_exists($full)) {
            abort(404);
        }

        $size = filesize($full);
        $mime = mime_content_type($full);

        // Suporte a Range (stream parcial)
        $range = $request->header('Range');

        if ($range) {
            list(, $range) = explode('=', $range, 2);

            if (str_contains($range, ',')) {
                return response("Multiple ranges not supported", 416);
            }

            $range = explode('-', $range);
            $start = intval($range[0]);
            $end = $range[1] === '' ? ($size - 1) : intval($range[1]);

            $length = $end - $start + 1;

            $stream = fopen($full, 'rb');
            fseek($stream, $start);

            $response = response()->stream(function() use ($stream, $length) {
                $buffer = 1024 * 1024; // 1MB

                while (!feof($stream) && $length > 0) {
                    $read = ($length > $buffer) ? $buffer : $length;
                    echo fread($stream, $read);
                    flush();
                    $length -= $read;
                }

                fclose($stream);
            }, 206, [
                'Content-Type'        => $mime,
                'Content-Length'      => $length,
                'Content-Range'       => "bytes $start-$end/$size",
                'Accept-Ranges'       => 'bytes',
            ]);

            return $response;
        }

        // Se não pedir Range, devolve tudo normal
        $stream = fopen($full, 'rb');

        return response()->stream(function() use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type'   => $mime,
            'Content-Length' => $size,
            'Accept-Ranges'  => 'bytes',
        ]);
    }
}

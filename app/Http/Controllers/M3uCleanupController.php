<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class M3uCleanupController extends Controller
{
    /**
     * Testa arquivos M3U/M3U8 e remove links quebrados
     */
    public function cleanup()
    {
        set_time_limit(1000);
        $dir = '/mnt/jellyflin/TV'; // ajuste seu diretório aqui

        if (!is_dir($dir)) {
            return "Diretório não existe: " . $dir;
        }

        $files = scandir($dir);

        $totalRemovidos = 0;
        $totalTestados = 0;

        foreach ($files as $file) {
            if (!preg_match('/\.m3u8?$/i', $file)) {
                continue;
            }

            $filePath = $dir . '/' . $file;
            $lines = explode("#EXTINF",file_get_contents($filePath));
            


            for ($i = 0; $i < count($lines); $i++) {

                $url = explode("\n", trim($lines[$i]))[0] ?? '';

                if ($this->isUrl($url) &&  !$this->urlBroken($url))    continue;


                unset($lines[$i]);
                file_put_contents($filePath, implode("#EXTINF", $lines));
            }
        }

        return response()->json([
            'status' => 'Finalizado'
        ]);
    }

    /**
     * Verifica se uma linha é uma URL válida
     */
    private function isUrl($line)
    {
        return filter_var($line, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Testa se a URL está quebrada
     */
    private function urlBroken($url)
    {
        try {
            $response = Http::timeout(10)->head($url);

            return ! $response->successful();
        } catch (\Exception $e) {
            return true; // qualquer erro considera quebrado
        }
    }
}

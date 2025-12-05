<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CollectionVideoController extends Controller
{
    private string $basePath = '/mnt/jellyflin';


    public function show($path)
    {

        set_time_limit(30);
        $fullPath = $this->basePath . '/' . $path;

        if (!is_dir($fullPath)) {
            abort(404);
        }

        $homes = $this->listDirectories($this->basePath);

        $items = $this->listDirectories($fullPath);


        return view('collections.show', [
            'items' => $items,
            'path' => $path,
            'homes' => $homes,
        ]);
    }

    private function listDirectories($path, $max = 0)
    {

        $items = scandir($path);
        $items = array_diff($items, ['.', '..', 'lost+found', '.Trash-1000','folder.png','folder.jpg','folder.webp']);
        usort($items, 'strnatcasecmp');

        $result = [];

        foreach ($items as $i => $item) {
            if ($max > 0 && $max == $i) return $result;
            $full = $path . '/' . $item;
            if (is_dir($full)) {

                $result[] = [
                    'name' => $item,
                    'full' => $full,
                    'is_dir' => is_dir($full),
                    'url' => $this->buildUrl($full),
                    'thumbnail' => $this->getThumbnail($full),
                    'id' => md5($full)
                ];
            }
            if (is_file($full)) {
                $result[] = [
                    'name' => $item,
                    'full' => $full,
                    'is_dir' => is_dir($full),
                    'url' => $this->buildUrl($full),
                    'thumbnail' => $this->getThumbnail($full),
                    'id' => md5($full)
                ];
            }


        }

        return $result;
    }


    private function getThumbnail($path)
    {

        // Pasta → ícone
        if (is_dir($path)) {
            return $this->getFisrtThumbnail($path);
        }


        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Imagens → usar o próprio arquivo
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $relative = str_replace('/mnt/jellyflin/', '', $path);
            return route('file.serve', ['path' => $relative]);
        }

        // Vídeos → gerar thumbnail
        if (in_array($ext, ['mp4', 'mkv', 'avi', 'mov', 'wmv'])) {

            // Nome do arquivo final da thumbnail
            $filename = md5($path) . '.jpg';

            // Caminho físico onde será salvo
            $thumbPath = storage_path("app/public/thumbnails/$filename");

            // Cria o diretório se não existir
            if (!is_dir(dirname($thumbPath))) {
                mkdir(dirname($thumbPath), 0777, true);
            }

            // Se ainda não existe → gerar
            if (!file_exists($thumbPath)) {
                $this->generateVideoThumbnail($path, $thumbPath);
            }


            // Se mesmo assim não existir: erro → usa ícone
            if (!file_exists($thumbPath)) {
                return asset("storage/icons/folder.png");
            }

            return asset("storage/thumbnails/$filename");
        }

        return asset('storage/icons/file.png');
    }


    private function buildUrl($fullPath)
    {
        $relative = str_replace($this->basePath . '/', '', $fullPath);

        // Clique em pasta → abre pasta
        if (is_dir($fullPath)) {
            return route('collections.show', $relative);
        }

        // Clique em arquivo → só carrega quando clicado
        return route('file.serve', $relative);
    }

    private function generateVideoThumbnail($videoPath, $thumbnailPath)
    {
        try {
            // Loga entrada
            \Log::info("THUMB - Iniciando geração", [
                'video' => $videoPath,
                'thumb_destino' => $thumbnailPath
            ]);

            // 1. Verificar se o arquivo existe
            if (!file_exists($videoPath)) {
                \Log::error("THUMB - Arquivo não encontrado: $videoPath");
                return false;
            }

            // 2. Verificar se Laravel consegue ler
            if (!is_readable($videoPath)) {
                \Log::error("THUMB - Laravel não tem permissão para LER o arquivo: $videoPath");
                return false;
            }

            // 3. Garantir diretório
            $dir = dirname($thumbnailPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $time = $this->getVideoDuration($videoPath);

            // 4. FFmpeg command
            $cmd = sprintf(
                'ffmpeg -ss ' . $time . ' -i "%s" -frames:v 1 -q:v 3 "%s" -y 2>&1',
                $videoPath,
                $thumbnailPath
            );


            \Log::info("THUMB - CMD executado: $cmd");

            // 5. Executar FFmpeg
            $output = shell_exec($cmd);

            \Log::info("THUMB - Saída FFmpeg:", [$output]);

            // 6. Verificar se criou
            if (!file_exists($thumbnailPath)) {
                \Log::error("THUMB - FFmpeg não gerou a thumbnail!", [
                    "destino" => $thumbnailPath
                ]);
                return false;
            }

            chmod($thumbnailPath, 0777);

            \Log::info("THUMB - Thumbnail gerada com sucesso", [
                'thumbnail' => $thumbnailPath
            ]);
            return true;
        } catch (\Exception $exception) {
            return false;
        }


    }


    function getVideoDuration($filePath)
    {
        // Comando ffprobe
        $cmd = "ffprobe -v quiet -print_format json -show_format \"$filePath\"";

        // Executa e captura saída
        $output = shell_exec($cmd);

        if (!$output) {
            return null;
        }

        $data = json_decode($output, true);

        if (isset($data['format']['duration'])) {
            $tempo = floatval($data['format']['duration']);
            $tempo = $tempo / 2;
            return gmdate("H:i:s", $tempo);

        }

        return null;
    }


    private function notVideo($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($ext, ['mp4', 'mkv', 'avi', 'mov', 'wmv', ""])) {
            return false;
        }
        return true;
    }

    private function getFisrtThumbnail($path)
    {

        if ( file_exists($path.DIRECTORY_SEPARATOR.'folder.png')) {

            $listFolders = explode(DIRECTORY_SEPARATOR, $path);
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

            return $protocolo.$_SERVER['HTTP_HOST'].'/file/'.$listFolders[(count($listFolders) - 2)].'/'. $listFolders[(count($listFolders) - 1)].'/'.'folder.png';
        }
        if ( file_exists($path.DIRECTORY_SEPARATOR.'folder.jpg')) {

            $listFolders = explode(DIRECTORY_SEPARATOR, $path);
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

            return $protocolo.$_SERVER['HTTP_HOST'].'/file/'.$listFolders[(count($listFolders) - 2)].'/'. $listFolders[(count($listFolders) - 1)].'/'.'folder.jpg';
        }
        if ( file_exists($path.DIRECTORY_SEPARATOR.'folder.webp')) {

            $listFolders = explode(DIRECTORY_SEPARATOR, $path);
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

            return $protocolo.$_SERVER['HTTP_HOST'].'/file/'.$listFolders[(count($listFolders) - 2)].'/'. $listFolders[(count($listFolders) - 1)].'/'.'folder.webp';
        }


        $items = $this->listDirectories($path, 10);

        foreach ($items as $item) {
            if (!$this->notVideo($item['name'])) {

                return $this->getThumbnail($item['full']);
            }

        }
        return asset('storage/icons/folder.png');
    }


}

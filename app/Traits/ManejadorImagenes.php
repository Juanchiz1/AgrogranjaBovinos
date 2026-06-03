<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait ManejadorImagenes
{
    
    protected function guardarImagen(UploadedFile $archivo, string $subdirectorio): string
    {
        // En producción usar Cloudinary
        if (config('app.env') === 'production' && config('cloudinary.cloud_name')) {
            return $this->subirCloudinary($archivo, $subdirectorio);
        }

        // En local guardar en disco
        $directorio = public_path("img/{$subdirectorio}");
        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }
        $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
        $archivo->move($directorio, $nombreArchivo);

        return "img/{$subdirectorio}/{$nombreArchivo}";
    }

    
    private function subirCloudinary(UploadedFile $archivo, string $subdirectorio): string
    {
        $cloudName  = config('cloudinary.cloud_name');
        $apiKey     = config('cloudinary.api_key');
        $apiSecret  = config('cloudinary.api_secret');
        $folder     = 'agrogranja/' . $subdirectorio;
        $timestamp  = time();

        $paramsToSign = [
            'folder'    => $folder,
            'timestamp' => $timestamp,
        ];
        ksort($paramsToSign);
        $signString = http_build_query($paramsToSign, '', '&', PHP_QUERY_RFC3986);
        $signature  = sha1($signString . $apiSecret);

        $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'file'      => new \CURLFile($archivo->getRealPath(), $archivo->getMimeType(), $archivo->getClientOriginalName()),
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'folder'    => $folder,
                'signature' => $signature,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['secure_url'])) {
            return $data['secure_url'];
        }

        // Fallback: guardar en disco si Cloudinary falla
        $directorio = public_path("img/{$subdirectorio}");
        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }
        $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
        $archivo->move($directorio, $nombreArchivo);
        return "img/{$subdirectorio}/{$nombreArchivo}";
    }

    /**
     * Elimina una imagen. Si es URL de Cloudinary la elimina via API,
     * si es ruta local la borra del disco.
     */
    protected function eliminarImagen(?string $ruta): void
    {
        if (!$ruta) return;

        if (str_starts_with($ruta, 'https://res.cloudinary.com')) {
            // Extraer public_id de la URL
            $cloudName = config('cloudinary.cloud_name');
            $apiKey    = config('cloudinary.api_key');
            $apiSecret = config('cloudinary.api_secret');

           
            if (preg_match('/\/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $ruta, $m)) {
                $publicId  = $m[1];
                $timestamp = time();
                $signature = sha1("public_id={$publicId}&timestamp={$timestamp}{$apiSecret}");

                $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy");
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => [
                        'public_id' => $publicId,
                        'api_key'   => $apiKey,
                        'timestamp' => $timestamp,
                        'signature' => $signature,
                    ],
                ]);
                curl_exec($ch);
                curl_close($ch);
            }
            return;
        }

        // Ruta local
        $rutaAbsoluta = public_path($ruta);
        if (file_exists($rutaAbsoluta)) {
            unlink($rutaAbsoluta);
        }
    }
}
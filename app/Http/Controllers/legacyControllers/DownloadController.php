<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    /**
     * Descargar la aplicación móvil APK
     */
    public function downloadMobileApp(Request $request)
    {
        try {
            $fileName = 'GarageMeetMobile.apk';
            $filePath = 'downloads/' . $fileName;

            // Verificar si el archivo existe
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'error' => 'Archivo no encontrado'
                ], 404);
            }

            // Obtener la ruta completa del archivo
            $fullPath = Storage::disk('public')->path($filePath);

            // Registrar la descarga para analytics (opcional)
            Log::info('APK Download', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Retornar el archivo para descarga
            return response()->download($fullPath, $fileName, [
                'Content-Type' => 'application/vnd.android.package-archive',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'public, max-age=31536000',
                'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000)
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading APK: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener información de la aplicación móvil
     */
    public function getAppInfo()
    {
        $fileName = 'GarageMeetMobile.apk';
        $filePath = 'downloads/' . $fileName;

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json([
                'error' => 'Archivo no encontrado'
            ], 404);
        }

        $fileSize = Storage::disk('public')->size($filePath);
        $lastModified = Storage::disk('public')->lastModified($filePath);

        return response()->json([
            'name' => $fileName,
            'size' => $fileSize,
            'size_formatted' => $this->formatBytes($fileSize),
            'version' => '1.0.0', // Puedes hacer esto dinámico
            'last_updated' => date('Y-m-d H:i:s', $lastModified),
            'download_url' => route('download.mobile-app')
        ]);
    }

    /**
     * Formatear bytes a formato legible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

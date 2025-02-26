<?php

namespace App\Traits;

use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait JsonDownloadTrait
{
    public function downloadZipCompressedJson(array $payload, string $fileName): StreamedResponse
    {
        $fileName = sprintf(
            '%s_%s.json',
            $fileName,
            now()->format('Y-m-d_H-i-s'),
        );

        return response()->streamDownload(function () use ($payload, $fileName) {

            $zip = new ZipArchive();
            $zipPath = tempnam(sys_get_temp_dir(), 'zip');
            $zip->open($zipPath, ZipArchive::CREATE);
            $zip->addFromString($fileName, json_encode($payload, JSON_PRETTY_PRINT));
            $zip->close();

            readfile($zipPath);

            unlink($zipPath);

        }, $fileName . '.zip', [ 'Content-Type' => 'application/zip' ]);
    }
}

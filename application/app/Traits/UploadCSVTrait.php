<?php

namespace App\Traits;

use League\Csv\Writer;
use League\Csv\Exception;
use League\Csv\CannotInsertRecord;
use Illuminate\Support\Facades\Storage;

trait UploadCSVTrait
{
    /**
     * @throws CannotInsertRecord|Exception
     */
    public function uploadCSV(string $fileName, array $csvRows): void
    {
        $storageInstance = Storage::disk(app()->environment('local') ? 'public' : 's3');
        $storageInstance->put($fileName, '');
        $fileContent = $storageInstance->get($fileName);
        $writer = Writer::createFromString($fileContent, 'w');
        $writer->insertAll($csvRows);
        $csvContent = $writer->toString();
        $storageInstance->put($fileName, $csvContent);
    }
}

<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Contracts\Container\BindingResolutionException;

trait JsonDownloadTrait
{
    /**
     * @throws BindingResolutionException
     */
    public function downloadJson(array $payload, $filename): Response
    {
        return response()->make(json_encode($payload, JSON_PRETTY_PRINT), 200, [
            'Content-type' => 'application/json',
            'Content-Disposition' => sprintf(
                'attachment; filename="%s_%s.json"',
                $filename,
                now()->format('Y-m-d_H-i-s'),
            ),
        ]);
    }
}

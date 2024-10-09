<?php

namespace App\Traits;

use Throwable;
use App\Models\AppError;
use Illuminate\Support\Facades\Log;

trait LogExceptionTrait
{
    function logException(string $message, Throwable $exception, array $metadata = []): void
    {
        $errorInfo = [
            ...$metadata,
            'request' => request()->toArray(),
            'error' => $exception->getMessage(),
            'file' => $exception->getFile() . ':' . $exception->getLine(),
            'previous' => $this->parsePreviousExceptions($exception->getPrevious()),
        ];

        try {
            AppError::create([
                'message' => substr($message, 0, 2048),
                'error' => json_encode($errorInfo),
            ]);
        } catch (Throwable) { }

        if (app()->environment('local')) {
            dd([
                'message' => $message,
                ...$errorInfo,
            ]);
        } else {
            Log::error($message, $errorInfo);
        }
    }

    function parsePreviousExceptions(Throwable|null $exception = null): array|null
    {
        $previous = null;
        if ($exception) {
            $previous = [
                'error' => $exception->getMessage(),
                'file' => $exception->getFile() . ':' . $exception->getLine(),
                'previous' => ($exception->getPrevious() ? $this->parsePreviousExceptions($exception->getPrevious()) : null),
            ];
        }
        return $previous;
    }
}

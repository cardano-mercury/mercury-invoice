<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 */
class AppError extends Model
{
    protected $fillable = [
        'message',
        'error',
    ];
}

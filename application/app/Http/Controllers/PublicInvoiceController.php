<?php

namespace App\Http\Controllers;

use App\Traits\HashIdTrait;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    use HashIdTrait;

    public function show(string $encodedId, Request $request)
    {
        dd([
            'TODO Invoice Viewer',
            'encodedId' => $encodedId,
            'decodedId' => $this->decodeId($encodedId),
        ]);
    }
}

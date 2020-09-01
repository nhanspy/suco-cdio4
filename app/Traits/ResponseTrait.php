<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    public function response($message = 'default', $data = null, $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}

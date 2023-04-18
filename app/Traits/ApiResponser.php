<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    /**
     * @param  string  $message
     * @param  string  $status
     * @param  array|null  $data
     * 
     * @return Response
     */
    public function apiResponse(
        string $message,
        string $status = Response::HTTP_OK,
        ?array $data = null
    ): Response {
        return response([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status);
    }
}

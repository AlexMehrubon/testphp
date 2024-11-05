<?php

namespace App\Traits;

trait JsonResponsableTrait
{

    /**
     * @param array $data
     * @param int $statusCode
     * @return string
     */
    private function jsonResponse(array $data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        return json_encode($data);
    }

}
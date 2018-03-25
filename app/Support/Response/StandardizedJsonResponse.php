<?php
namespace App\Support;

use Illuminate\Http\JsonResponse;

class StandardizedJsonResponse
{
    private $metas = [];
    private $data = [];

    public function __construct($data = null, $meta = [])
    {
        $this->data;
    }

    public static function fromJsonResponse(JsonResponse $response)
    {

    }
}

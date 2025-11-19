<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    private function notFoundResponse(string $item, int $id): JsonResponse
    {
        return response()->json(['message' => "$item $id not found"], 404);
    }
}

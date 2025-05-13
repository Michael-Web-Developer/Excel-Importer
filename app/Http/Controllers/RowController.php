<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\RowResource;
use App\Models\Row;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class RowController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $rows = Row::all();
            $grouped = $rows->groupBy(function (Row $row) {
                return $row->date->format('d.m.Y');
            });

            $result = $grouped->map(function (Collection $rows) {
                return $rows->map(function (Row $item) {
                    return RowResource::make($item);
                });
            })->toArray();

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve rows.'], 500);
        }
    }
}

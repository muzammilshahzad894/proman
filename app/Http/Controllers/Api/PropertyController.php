<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PropertyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    public function __construct(protected PropertyRepositoryInterface $propertyRepository)
    {}
    
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $properties = $this->propertyRepository
                ->getAllRecordsQuery()
                ->paginate($request->per_page ?? config('pagination.per_page', 10));

            return PropertyResource::collection($properties);
        } catch (\Exception $e) {
            Log::error("Error in " . __CLASS__ . " on line " . __LINE__ . " while getting countries. Exception: {$e->getMessage()}");
            return response()->json([
                'error' => 'Something went wrong while getting the countries. Please try again later.'
            ], 500);
        }
    }
}
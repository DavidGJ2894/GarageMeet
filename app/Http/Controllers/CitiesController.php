<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CitiesServiceInterface;
use App\Services\CitiesService;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    private CitiesServiceInterface $citiesService;

    public function __construct(CitiesServiceInterface $citiesService)
    {
        $this->citiesService = $citiesService;
    }

    public function findByName(string $name)
    {
        try {
            $cities = $this->citiesService->findCityByName($name);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving city'], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            if (!$query || strlen($query) < 2) {
                return response()->json([]);
            }
            $cities = $this->citiesService->findCityByName($query);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error searching cities'], 500);
        }
    }
}

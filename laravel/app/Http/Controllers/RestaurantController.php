<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Restaurant::all());
    }

    public function show(int $id): JsonResponse
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json($restaurant);
    }

    public function store(Request $request): JsonResponse
    {
        $restaurant = Restaurant::create($request->all());
        return response()->json($restaurant, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $restaurant->update($request->all());
        return response()->json($restaurant);
    }

    public function destroy(int $id): JsonResponse
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $restaurant->delete();
        return response()->json(['status' => 'deleted']);
    }
}
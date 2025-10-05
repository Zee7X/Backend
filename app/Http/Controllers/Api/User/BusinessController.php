<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with('category');

        if ($request->has('category')) {
            $slug = $request->category;
            $query->whereHas('category', fn($q) => $q->where('slug', $slug));
        }

        if ($request->has('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $businesses = $query->paginate(15);
        return response()->json($businesses);
    }

    public function show(Business $business)
    {
        $business->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Business details retrieved successfully.',
            'data' => $business,
        ]);
    }
}

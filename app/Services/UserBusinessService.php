<?php

namespace App\Services;

use App\Models\Business;
use Illuminate\Http\Request;

class UserBusinessService
{
    public function getList(Request $request)
    {
        $query = Business::with('category');

        if ($slug = $request->get('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $slug));
        }

        if ($term = $request->get('search')) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        return $query->paginate($perPage);
    }

    public function getDetail(Business $business)
    {
        return $business->load('category');
    }
}

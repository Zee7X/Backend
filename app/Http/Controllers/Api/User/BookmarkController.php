<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = $request->user()
            ->bookmarks()
            ->with('category')
            ->paginate(15);

        return response()->json($bookmarks);
    }

    public function store(Request $request, Business $business)
    {
        $user = $request->user();

        if ($user->bookmarks()->where('business_id', $business->id)->exists()) {
            return response()->json(['message' => 'Already bookmarked'], 409);
        }

        $user->bookmarks()->attach($business->id);

        return response()->json(['message' => 'Bookmarked successfully']);
    }

    public function destroy(Request $request, Business $business)
    {
        $user = $request->user();
        $user->bookmarks()->detach($business->id);

        return response()->json(['message' => 'Bookmark removed']);
    }
}

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Services\BookmarkService;

class BookmarkController extends Controller
{
    protected $service;

    public function __construct(BookmarkService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $bookmarks = $this->service->getBookmarks($request->user(), 15);

        return response()->json($bookmarks);
    }

    public function store(Request $request, Business $business)
    {
        $user = $request->user();

        if ($this->service->hasBookmark($user, $business)) {
            return response()->json(['message' => 'Already bookmarked'], 409);
        }

        $this->service->addBookmark($user, $business);

        return response()->json(['message' => 'Bookmarked successfully'], 201);
    }

    public function destroy(Request $request, Business $business)
    {
        $this->service->removeBookmark($request->user(), $business);

        return response()->json(['message' => 'Bookmark removed']);
    }
}

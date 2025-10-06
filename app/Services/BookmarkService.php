<?php

namespace App\Services;

use App\Models\User;
use App\Models\Business;

class BookmarkService
{
    public function getBookmarks(User $user, int $perPage = 15)
    {
        return $user->bookmarks()->with('category')->paginate($perPage);
    }

    public function hasBookmark(User $user, Business $business): bool
    {
        return $user->bookmarks()->where('business_id', $business->id)->exists();
    }

    public function addBookmark(User $user, Business $business): void
    {
        $user->bookmarks()->attach($business->id);
    }

    public function removeBookmark(User $user, Business $business): void
    {
        $user->bookmarks()->detach($business->id);
    }
}

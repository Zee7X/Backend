<?php

namespace App\Services;

use App\Models\Business;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BusinessService
{
    public function getDataTable(Request $request)
    {
        $query = Business::with('category')->select('businesses.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', fn(Business $b) => $b->category ? [
                'id'   => $b->category->id,
                'name' => $b->category->name,
                'slug' => $b->category->slug,
            ] : null)
            ->addColumn('logo_url', fn(Business $b) => $b->logo_path ? asset('storage/' . $b->logo_path) : null)
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
                }
            })
            ->order(function ($query) use ($request) {
                if ($order = $request->get('order')[0] ?? null) {
                    $columns = $request->get('columns');
                    $columnName = $columns[$order['column']]['data'] ?? 'id';
                    $dir = $order['dir'] ?? 'asc';
                    $query->orderBy($columnName, $dir);
                }
            })
            ->make(true);
    }

    public function create(array $data): Business
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['logo_path'])) {
                $data['logo_path'] = $data['logo_path']->store('logos', 'public');
            }

            $data['slug'] = $this->generateSlug($data['slug'] ?? null, $data['name']);

            return Business::create($data);
        });
    }

    public function update(Business $business, array $data): Business
    {
        return DB::transaction(function () use ($business, $data) {
            if (!empty($data['logo_path']) && $data['logo_path'] instanceof \Illuminate\Http\UploadedFile) {
                if ($business->logo_path && Storage::disk('public')->exists($business->logo_path)) {
                    Storage::disk('public')->delete($business->logo_path);
                }
                $data['logo_path'] = $data['logo_path']->store('logos', 'public');
            } else {
                unset($data['logo_path']);
            }

            if (isset($data['name']) || isset($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['slug'] ?? null, $data['name'] ?? $business->name, $business->id);
            }

            $business->update($data);

            return $business->fresh();
        });
    }

    public function delete(Business $business): void
    {
        DB::transaction(function () use ($business) {
            if ($business->logo_path && Storage::disk('public')->exists($business->logo_path)) {
                Storage::disk('public')->delete($business->logo_path);
            }

            $business->delete();
        });
    }

    protected function generateSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($slug ?: $name);

        $exists = Business::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'slug' => ['The slug has already been taken. Please choose another one.']
            ]);
        }

        return $slug;
    }
}

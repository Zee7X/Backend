<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exceptions\CategoryException;

class CategoryService
{
    public function getDataTable(Request $request)
    {
        $query = Category::query();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($order = $request->input('order.0')) {
            $columns = $request->input('columns', []);
            $columnName = $columns[$order['column']]['data'] ?? 'id';
            $dir = $order['dir'] ?? 'asc';
            $query->orderBy($columnName, $dir);
        }

        return DataTables::of($query)->addIndexColumn()->make(true);
    }

    public function create(array $data): Category
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if (!isset($data['slug']) || $data['slug'] === '') {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->fill($data);
        $category->save();

        return $category;
    }

    public function delete(Category $category): void
    {
        try {
            $category->delete();
        } catch (\Exception $e) {
            throw new CategoryException('Failed to delete category: ' . $e->getMessage());
        }
    }
}

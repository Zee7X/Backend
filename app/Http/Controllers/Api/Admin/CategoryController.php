<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Category::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
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

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::create($data);

        return (new CategoryResource($category))
            ->additional(['message' => 'Category has been created successfully.']);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if (!$request->has('slug')) {
            $data['slug'] = Str::slug($data['name']);
        } elseif (isset($data['slug']) && $data['slug'] === '') {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->fill($data);
        $category->save();

        return (new CategoryResource($category))
            ->additional(['message' => 'Category has been updated successfully.']);
    }

    public function destroy(Category $category)
    {
        try {
            $this->service->delete($category);
            return response()->json([
                'message' => 'Category has been deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFilterRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(CategoryFilterRequest $request)
    {
        return $this->service->getDataTable($request);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create($request->validated());
        return (new CategoryResource($category))
            ->additional(['message' => 'Category has been created successfully.']);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->service->update($category, $request->validated());
        return (new CategoryResource($category))
            ->additional(['message' => 'Category has been updated successfully.']);
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return response()->json(['message' => 'Category has been deleted successfully.'], 200);
    }
}

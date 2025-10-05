<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Services\BusinessService;
use App\Models\Business;
use Yajra\DataTables\Facades\DataTables;

class BusinessController extends Controller
{
    protected $service;

    public function __construct(BusinessService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $query = Business::with('category')->select('businesses.*');
        return DataTables::of($query)
            ->addColumn('category', fn(Business $b) => $b->category->name)
            ->addColumn('logo_url', fn(Business $b) => $b->logo_path ? asset('storage/' . $b->logo_path) : null)
            ->make(true);
    }

    public function store(StoreBusinessRequest $request)
    {
        $business = $this->service->create($request->validated());

        return (new BusinessResource($business))
            ->additional(['message' => 'Business has been created successfully.']);
    }

    public function update(UpdateBusinessRequest $request, Business $business)
    {
        $data = $request->validated();

        $updated = $this->service->update($business, $data);

        return response()->json([
            'message' => 'Business updated successfully.',
            'data' => new BusinessResource($updated),
        ]);
    }

    public function destroy(Business $business)
    {
        try {
            $this->service->delete($business);
            return response()->json([
                'message' => 'Business deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Business.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

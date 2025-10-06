<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Services\BusinessService;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    protected $service;

    public function __construct(BusinessService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getDataTable($request);
    }

    public function store(StoreBusinessRequest $request)
    {
        $business = $this->service->create($request->validated());

        return (new BusinessResource($business))
            ->additional(['message' => 'Business has been created successfully.']);
    }

    public function update(UpdateBusinessRequest $request, Business $business)
    {
        $updated = $this->service->update($business, $request->validated());

        return (new BusinessResource($updated))
            ->additional(['message' => 'Business has been updated successfully.']);
    }

    public function destroy(Business $business)
    {
        $this->service->delete($business);

        return response()->json(['message' => 'Business has been deleted successfully.']);
    }
}

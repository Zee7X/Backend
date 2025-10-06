<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Services\UserBusinessService;
use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    protected $service;

    public function __construct(UserBusinessService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $businesses = $this->service->getList($request);

        return BusinessResource::collection($businesses)
            ->additional([
                'success' => true,
                'message' => 'Businesses retrieved successfully.'
            ]);
    }

    public function show(Business $business)
    {
        $detail = $this->service->getDetail($business);

        return (new BusinessResource($detail))
            ->additional([
                'success' => true,
                'message' => 'Business details retrieved successfully.'
            ]);
    }
}

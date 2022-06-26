<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\brand\StoreRequest;
use App\Http\Requests\brand\UpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $perPage = request('perpage') ? intval(request('perpage')) : 50;
        $brands = Brand::paginate($perPage);
        return BrandResource::collection($brands)
            ->additional([
                'success' => true,
            ]);
    }

    public function store(StoreRequest $request)
    {
        $brand = Brand::create($request->all());
        return (new BrandResource($brand))
            ->additional([
                'success' => true,
            ]);
    }

    public function show(Brand $brand)
    {
        return (new BrandResource($brand))
            ->additional([
                'success' => true,
            ]);
    }

    public function update(UpdateRequest $request, Brand $brand)
    {
        $brand->update($request->all());
        return (new BrandResource($brand))
            ->additional([
                'success' => true,
            ]);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return (new BrandResource($brand))
            ->additional([
                'success' => true
            ]);
    }
}

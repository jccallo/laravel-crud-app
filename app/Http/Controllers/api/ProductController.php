<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\product\StoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $perPage = request('perpage') ? intval(request('perpage')) : 50;
        $products = Product::paginate($perPage);
        return ProductResource::collection($products)
            ->additional([
                'success' => true,
            ]);
    }

    public function create() {
        return response()->json([
            'data' => [
                'brands' => Brand::orderBy('id')->pluck('name', 'id'),
                'tags' => Tag::orderBy('id')->pluck('name', 'id'),
            ],
            'success' => true,
        ]);
    }

    public function store(StoreRequest $request)
    {
        $product = $request->all(); // el request trae consigo al campo tags pero laravel solo toma los campos correctos para crear (falta probar si se puede usar except o only)
        $tags = explode(',', $product['tags']); // extraer el string que representa a un array de tags y lo convierto a array
        if ($image = $request->file('image')) {
            $destiny = 'storage/image/'; // tambien funciona: public_path('storage/images')
            $name = date('YmdHis') . "." . $image->getClientOriginalExtension(); // tambien se puede usar; time() y uniqid()
            $image->move($destiny, $name);
            $product['image'] = $name;
        }
        $product = Product::create($product);
        $product->tags()->sync($tags); // le paso esos tags
        return (new ProductResource($product))
            ->additional([
                'success' => true,
            ]);
    }

    public function show(Product $product)
    {
        return (new ProductResource($product))
            ->additional([
                'tags' => $product->tags()->pluck('name', 'id'),
                'success' => true,
            ]);
    }

    public function update(StoreRequest $request, Product $product)
    {
        $productRequest = $request->all();
        if ($image = $request->file('image')) {
            $destiny = 'storage/image/'; // tambien funciona: public_path('storage/images')
            $name = date('YmdHis') . "." . $image->getClientOriginalExtension(); // tambien se puede usar; time() y uniqid()
            $image->move($destiny, $name);
            $productRequest['image'] = $name;

            unlink(public_path('storage/image/' . $product->image));
        }
        $product->update($productRequest);
        return (new ProductResource($product))
            ->additional([
                'success' => true,
            ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return (new ProductResource($product))
            ->additional([
                'success' => true
            ]);
    }
}

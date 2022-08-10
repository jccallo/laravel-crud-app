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

    public function create()
    {
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
        // request trae consigo al campo tags pero laravel solo toma los campos correctos para crear (falta probar si se puede usar except o only)
        $product = $request->all();

        // rescato los tag que viene en forma de cadena, ejm: "1,2,3,4"
        $tags = $product['tags'] ?? null; // tambien se puede usar la funcion isset() o el operador ternariopara manejar el error

        // guardo la imagen
        if ($image = $request->file('image')) {
            $destiny = 'storage/image/'; // tambien funciona: public_path('storage/images')
            $name = date('YmdHis') . "." . $image->getClientOriginalExtension(); // tambien se puede usar; time() y uniqid()
            $image->move($destiny, $name);
            $product['image'] = $name;
        }

        // guardo el producto
        $product = Product::create($product);

        // guardo las etiquetas
        if (!empty($tags)) { // verifico que guarde solo si no esta vacio
            $tags = explode(',', $tags); // $tags se convierte en un array, por ejm: ["1","2","3","4"]
            $product->tags()->sync($tags);
        }

        return (new ProductResource($product))
            ->additional([
                'success' => true,
            ]);
    }

    public function show(Product $product)
    {
        return (new ProductResource($product))
            ->additional([
                'brand' => $product->brand,
                'tags' => $product->tags()->pluck('name', 'id'),
                'success' => true,
            ]);
    }

    public function update(StoreRequest $request, Product $product) // en este caso se utiliza el mismo request
    {
        // guardo la informacion del request
        $productRequest = $request->all();

        // rescato los tag que viene en forma de cadena, ejm: "1,2,3,4"
        $tags = $productRequest['tags'] ?? null; // para que no provoque un error porque no encuentra dicho key, si no se manda tags

        // guardo la imagen
        if ($image = $request->file('image')) {
            $destiny = 'storage/image/'; // tambien funciona: public_path('storage/images')
            $name = date('YmdHis') . "." . $image->getClientOriginalExtension(); // tambien se puede usar; time() y uniqid()
            $image->move($destiny, $name);
            $productRequest['image'] = $name;

            unlink(public_path('storage/image/' . $product->image)); // borro la imagen anterior
        }

        // actualizo el producto
        $product->update($productRequest);

        // guardo las etiquetas
        if (!empty($tags)) { // verifico que guarde solo si no esta vacio
            $tags = explode(',', $tags); // $tags se convierte en un array, por ejm: ["1","2","3","4"]
            $product->tags()->sync($tags);
        }

        return (new ProductResource($product))
            ->additional([
                'success' => true,
            ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        unlink(public_path('storage/image/' . $product->image));
        return (new ProductResource($product))
            ->additional([
                'success' => true,
            ]);
    }
}

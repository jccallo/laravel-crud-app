<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\StoreRequest;
use App\Http\Requests\customer\UpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $perPage = request('perpage') ? intval(request('perpage')) : 50;
        $customers = Customer::paginate($perPage);
        return CustomerResource::collection($customers)
            ->additional([
                'success' => true,
            ]);
    }

    public function store(StoreRequest $request)
    {
        $customer = $request->all();

        if ($image = $request->file('avatar')) {
            $destiny = 'storage/image/'; // tambien funciona: public_path('storage/images')
            $name = date('YmdHis') . "." . $image->getClientOriginalExtension(); // tambien se puede usar; time() y uniqid()
            $image->move($destiny, $name);
            $customer['avatar'] = $name;
        }

        $customer = Customer::create($customer);

        return (new CustomerResource($customer))
            ->additional([
                'success' => true,
            ]);
    }

    public function show(Customer $customer)
    {
        return (new CustomerResource($customer))
            ->additional([
                'success' => true,
            ]);
    }

    public function update(UpdateRequest $request, Customer $customer)
    {
        $customerRequest = $request->all();

        if ($image = $request->file('avatar')) {
            $destiny = 'storage/image/'; // tambien funciona: public_path('storage/images')
            $name = date('YmdHis') . "." . $image->getClientOriginalExtension(); // tambien se puede usar; time() y uniqid()
            $image->move($destiny, $name);
            $customerRequest['avatar'] = $name;

            unlink(public_path('storage/image/' . $customer->avatar));
        }

        $customer->update($customerRequest);

        return (new CustomerResource($customer))
            ->additional([
                'success' => true,
            ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        unlink(public_path('storage/image/' . $customer->avatar)); // borra su imagen. Puede ir antes de borrar inclusive
        return (new CustomerResource($customer))
            ->additional([
                'success' => true
            ]);
    }
}

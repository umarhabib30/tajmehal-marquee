<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Dish;
use App\Models\DishPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    public function index()
    {
        $values = Customer::all();
        $data = [
            'heading' => 'Customers',
            'title'   => 'View Customers',
            'active'  => 'customers',
            'values'  => $values,
        ];
        return view('admin.customer.index', $data);
    }

    public function create()
    {
        $data = [
            'heading' => 'Add Customer',
            'title'   => 'Create Customer',
            'active'  => 'customers',
        ];
        return view('admin.customer.create', $data);
    }

    public function store(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make(
            $request->all(),
            [
                'name'         => 'required|string|max:255',
                'email'        => 'email|max:255',
                'phone'        => 'required|digits:11|unique:customers,phone',
                'address'      => 'required|string|max:500',
            ]
        );

        // ✅ If validation fails, return with toaster-like message
        if ($validator->fails()) {
            if ($validator->errors()->has('email')) {
                return redirect()->back()->with('error', 'This email address is already taken!')->withInput();
            }

            if ($validator->errors()->has('idcardnumber')) {
                return redirect()->back()->with('error', 'This ID Card Number is already registered!')->withInput();
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Save new record
       $customer=  Customer::create($request->all());
       return redirect()->route('admin.booking.createWithCustomer',$customer->id)->with('success', 'Customer created successfully!');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $data = [
            'heading'  => 'Edit Customer',
            'title'    => 'Update Customer',
            'active'   => 'customers',
            'customer' => $customer,
        ];
        return view('admin.customer.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // ✅ Validation
        $validator = Validator::make(
            $request->all(),
            [
                'name'         => 'required|string|max:255',
                'email'        => 'required|email|max:255',
                'phone'        => 'required|digits:11|unique:customers,phone,' . $id,
                'idcardnumber' => [ 'regex:/^\d{5}-\d{7}-\d{1}$/'],
                'address'      => 'required|string|max:500',
            ]
        );

        // ✅ If validation fails, show toaster-style message
        if ($validator->fails()) {
            if ($validator->errors()->has('email')) {
                return redirect()->back()->with('error', 'This email address is already taken!')->withInput();
            }

            if ($validator->errors()->has('idcardnumber')) {
                return redirect()->back()->with('error', 'This ID Card Number is already registered!')->withInput();
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Update record
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customer.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
    }
}

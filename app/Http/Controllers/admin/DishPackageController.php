<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\DishPackage;

class DishPackageController extends Controller
{
    /**
     * Display a listing of the packages.
     */
    public function index()
    {
        $data = [
            'title'    => 'Dish Packages',
            'heading'  => 'All Dish Packages',
            'active'   => 'dish-packages',
            'packages' => DishPackage::with('dishes')->latest()->get(),
        ];

        return view('admin.dish_package.index', $data);
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        $data = [
            'title'   => 'Create Dish Package',
            'heading' => 'Add New Package',
            'active'  => 'dish-packages',
            'dishes'  => Dish::orderBy('name')->get(),
        ];

        return view('admin.dish_package.create', $data);
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'dishes'    => 'required|array|min:1',
            'dishes.*'  => 'exists:dishes,id',
        ]);

        $package = DishPackage::create([
            'name' => $request->input('name'),
        ]);

        $package->dishes()->attach($request->input('dishes'));

        return redirect()
            ->route('admin.dish_package.index')
            ->with('success', 'Dish package created successfully.');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit($id)
    {
        $package = DishPackage::with('dishes')->findOrFail($id);
        $selectedIds = $package->dishes->pluck('id')->toArray();

        $data = [
            'title'           => 'Edit Dish Package',
            'heading'         => 'Update Package',
            'active'          => 'dish-packages',
            'package'         => $package,
            'selectedDishes'  => $package->dishes,
            'dishes'          => Dish::whereNotIn('id', $selectedIds)->orderBy('name')->get(),
        ];

        return view('admin.dish_package.edit', $data);
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'dishes'    => 'required|array|min:1',
            'dishes.*'  => 'exists:dishes,id',
        ]);

        $package = DishPackage::findOrFail($id);
        $package->update(['name' => $request->input('name')]);
        $package->dishes()->sync($request->input('dishes'));

        return redirect()
            ->route('admin.dish_package.index')
            ->with('success', 'Dish package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy($id)
    {
        $package = DishPackage::findOrFail($id);
        $package->dishes()->detach();
        $package->delete();

        return response()->json(['success' => true]);
    }
}

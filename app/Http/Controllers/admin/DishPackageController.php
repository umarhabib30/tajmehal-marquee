<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DishPackage;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishPackageController extends Controller
{
    public function index()
    {
        $packages = DishPackage::latest()->get();
        $title = "Dish Packages";
        $active = "dish_package";
        $heading = "All Dish Packages";

        return view('admin.dish_package.index', compact('packages', 'title', 'active', 'heading'));
    }

    public function create()
    {
        $dishes = Dish::all();
        $title = "Create Dish Package";
        $active = "dish_package";
        $heading = "Add New Dish Package";

        return view('admin.dish_package.create', compact('dishes', 'title', 'active', 'heading'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'one_dish' => 'nullable|array',
            'two_dish' => 'nullable|array',
        ]);

        DishPackage::create($validated);

        return redirect()->route('admin.dish_package.index')->with('success', 'Dish Package created successfully!');
    }

    public function edit(DishPackage $dishPackage)
    {
        $dishes = Dish::all();
        $title = "Edit Dish Package";
        $active = "dish_package";
        $heading = "Update Dish Package";

        return view('admin.dish_package.edit', compact('dishPackage', 'dishes', 'title', 'active', 'heading'));
    }

    public function update(Request $request, DishPackage $dishPackage)
    {
        $validated = $request->validate([
            'one_dish' => 'nullable|array',
            'two_dish' => 'nullable|array',
        ]);

        $dishPackage->update($validated);

        return redirect()->route('admin.dish_package.index')->with('success', 'Dish Package updated successfully!');
    }

    public function destroy(DishPackage $dishPackage)
    {
        $dishPackage->delete();
        return redirect()->route('admin.dish_package.index')->with('success', 'Dish Package deleted successfully!');
    }
}

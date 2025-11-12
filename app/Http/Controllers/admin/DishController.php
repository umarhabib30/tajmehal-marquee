<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index()
    {
        $values = Dish::all();
        $data = [
            'heading' => 'Dish',
            'title'   => 'View Dish',
            'active'  => 'dishes',
            'values'  => $values,
        ];
        return view('admin.dishes.index', $data);
    }

    public function create()
    {
        $data = [
            'heading' => 'Dish',
            'title'   => 'Add Dish',
            'active'  => 'dishes'
        ];
        return view('admin.dishes.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
        ]);

        // ✅ Check if dish with same name already exists
        if (Dish::where('name', $request->name)->exists()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Dish name already exists!');
        }

        Dish::create([
            'name'           => $request->name,
        ]);

        return redirect()->back()->with('success', 'Dish added successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $dish = Dish::findOrFail($id);
        $data = [
            'heading' => 'Dish',
            'title'   => 'Edit Dish',
            'active'  => 'dishes',
            'dish'    => $dish,
        ];
        return view('admin.dishes.edit', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255|unique:dishes,name,' . $request->id,
        ]);

        $dish = Dish::findOrFail($request->id);

        $dish->update([
            'name'           => $request->name,
        ]);

        return redirect()
            ->route('dishes.index')
            ->with('success', 'Dish updated successfully!');
    }

    public function destroy(string $id)
    {
        $dish = Dish::findOrFail($id);
        $dish->delete();

        return redirect()
            ->back()
            ->with('success', 'Dish deleted successfully!');
    }
}

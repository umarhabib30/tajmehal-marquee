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
            'active'  => 'dish',
            'values'  => $values,
        ];
        return view('admin.dishes.index', $data);
    }

    public function create()
    {
        $data = [
            'heading' => 'Dish',
            'title'   => 'Enter Dish',
            'active'  => 'dish'
        ];
        return view('admin.dishes.create', $data);
    }

    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'name'           => 'required|string|max:255',
            'price_per_head' => 'required|numeric|min:0',
        ]);

        // ✅ Save
        Dish::create([
            'name'           => $request->name,
            'price_per_head' => $request->price_per_head,
        ]);

        // ✅ Redirect with Success Message
        return redirect()
            ->route('dishes.index')
            ->with('success', 'Dish created successfully!');
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
            'active'  => 'dish',
            'dish'    => $dish,
        ];
        return view('admin.dishes.edit', $data);
    }

    public function update(Request $request)
    {

        $request->validate([
            'name'           => 'required|string|max:255',
            'price_per_head' => 'required|numeric|min:0',
        ]);

        $dish = Dish::findOrFail($request->id);


        $dish->update([
            'name'           => $request->name,
            'price_per_head' => $request->price_per_head,
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

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $inventories = $category
            ? Inventory::where('category', $category)->get()
            : Inventory::all();

        $data = [
            'heading' => 'Inventory',
            'title' => 'View Inventory',
            'active' => 'inventory',
            'inventories' => $inventories,
            'category' => $category,
        ];

        return view('admin.inventory.index', $data);
    }

    public function create(Request $request)
    {
        $category = $request->get('category');

        $data = [
            'heading' => 'Inventory',
            'title' => 'Add Inventory Item',
            'active' => 'inventory',
            'category' => $category,
        ];

        return view('admin.inventory.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'quantity' => 'required|integer',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Item added successfully.');
    }

    public function edit(Inventory $inventory)
    {
        $data = [
            'heading' => 'Inventory',
            'title' => 'Edit Inventory Item',
            'active' => 'inventory',
            'inventory' => $inventory,
        ];

        return view('admin.inventory.edit', $data);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Item deleted successfully.');
    }
}

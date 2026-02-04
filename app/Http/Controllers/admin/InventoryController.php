<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function foodInventory()
    {
        $data = [
            'heading' => 'Inventory',
            'title' => 'Food Inventory',
            'active' => 'inventory',
            'inventories' => Inventory::where('category', 'Food')->get(),
        ];
        return view('admin.inventory.food', $data);
    }

    public function decorationInventory()
    {
        $data = [
            'heading' => 'Inventory',
            'title' => 'Decoration Inventory',
            'active' => 'inventory',
            'inventories' => Inventory::where('category', 'Decoration')->get(),
        ];
        return view('admin.inventory.decoration', $data);
    }

    public function furnitureInventory()
    {
        $data = [
            'heading' => 'Inventory',
            'title' => 'Furniture Inventory',
            'active' => 'inventory',
            'inventories' => Inventory::where('category', 'Furniture')->get(),
        ];
        return view('admin.inventory.furniture', $data);
    }

    public function crockeryInventory()
    {
        $data = [
            'heading' => 'Inventory',
            'title' => 'Crockery Inventory',
            'active' => 'inventory',
            'inventories' => Inventory::where('category', 'Crockery')->get(),
        ];
        return view('admin.inventory.crockery', $data);
    }

    public function electronicsInventory()
    {
        $data = [
            'heading' => 'Inventory',
            'title' => 'Electronics Inventory',
            'active' => 'inventory',
            'inventories' => Inventory::where('category', 'Electronics')->get(),
        ];
        return view('admin.inventory.electronics', $data);
    }

    public function stockInventory($id)
    {
        $inventory = Inventory::with(['stocks' => function ($query) {
            $query->orderBy('date', 'desc');
        }])->findOrFail($id);

        // Calculate totals (decimals safe)
        $total_in = (float) $inventory->stocks->sum('quantity_in');
        $total_out = (float) $inventory->stocks->sum('quantity_out');
        $current_stock = $total_in - $total_out;

        $data = [
            'heading' => 'Inventory',
            'title' => 'Inventory Stock',
            'active' => 'inventory',
            'inventory' => $inventory,
            'stocks' => $inventory->stocks,
            'total_in' => $total_in,
            'total_out' => $total_out,
            'current_stock' => $current_stock,
        ];

        return view('admin.inventory.stock', $data);
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'type' => 'required|in:add,take',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_unit' => 'nullable|numeric|min:0',
            'id' => 'required|exists:inventories,id',
            // optional fields (if you send them from form)
            'warranty_period' => 'nullable|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::findOrFail($request->id);

        $qty = (float) $request->quantity;
        $available = (float) $inventory->quantity;

        // message unit (Pieces / Kg)
        $unit = $inventory->quantity_type;

        // 🔍 Check available stock before taking
        if ($request->type === 'take' && $qty > $available) {
            return redirect()->back()->with('error', "Available stock is only {$available} {$unit}.");
        }

        // Record transaction (store decimals)
        InventoryStock::create([
            'inventory_id' => $inventory->id,
            'date' => now(),
            'quantity_in' => $request->type === 'add' ? $qty : 0,
            'quantity_out' => $request->type === 'take' ? $qty : 0,
            'price_per_unit' => $request->price_per_unit,
            'warranty_period' => $request->warranty_period ?? null,
            'supplier_name' => $request->supplier_name ?? null,
        ]);

        // Update main inventory quantity (decimals safe)
        if ($request->type === 'add') {
            $inventory->quantity = $available + $qty;
        } else {
            $inventory->quantity = $available - $qty;
        }
        $inventory->save();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Display a listing of the inventory items.
     */
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

    /**
     * Show the form for creating a new inventory item.
     */
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

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        // Manual validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:Food,Electronics,Furniture,Decoration,Crockery',
            'quantity_type' => 'required|in:Pieces,Kg',
            'quantity' => 'nullable|numeric|min:0',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        // Check if name already exists
        if (Inventory::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Inventory item with this name already exists.')->withInput();
        }

        // Create record (decimal safe)
        Inventory::create([
            'name' => $request->name,
            'category' => $request->category,
            'quantity_type' => $request->quantity_type,
            'quantity' => $request->quantity ?? 0,
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Inventory item added successfully.');
    }

    /**
     * Show the form for editing an existing inventory item.
     */
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

    /**
     * Update an existing inventory item in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        // Manual validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:Food,Electronics,Furniture,Decoration,Crockery',
            'quantity_type' => 'required|in:Pieces,Kg',
            'quantity' => 'required|numeric|min:0',
        ]);

        // Return only the first validation error
        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return redirect()
                ->back()
                ->with('error', $firstError)
                ->withInput();
        }

        // Check for duplicate name (unique except itself)
        $exists = Inventory::where('name', $request->name)
            ->where('id', '!=', $inventory->id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->with('error', 'An inventory item with this name already exists.')
                ->withInput();
        }

        // Update inventory (decimal safe)
        $inventory->update([
            'name' => $request->name,
            'category' => $request->category,
            'quantity_type' => $request->quantity_type,
            'quantity' => $request->quantity,
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }
}

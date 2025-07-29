<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Warehouse::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $warehouses = $query->latest()->paginate(15);

        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'manager' => 'nullable|string|max:255',
            'capacity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager' => $request->manager,
            'capacity' => $request->capacity,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['products']);
        return view('warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'manager' => 'nullable|string|max:255',
            'capacity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager' => $request->manager,
            'capacity' => $request->capacity,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        // Check if warehouse has products
        if ($warehouse->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete warehouse with associated products.');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
}

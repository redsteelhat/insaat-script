<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors
     */
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('tax_number', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by active status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $vendors = $query->latest()->paginate(20);

        // Get unique categories for filter
        $categories = Vendor::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('admin.vendors.index', compact('vendors', 'categories'));
    }

    /**
     * Show the form for creating a new vendor
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Store a newly created vendor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:vendors,code',
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'tax_office' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Vendor::create($validated);

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Tedarikçi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified vendor
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['purchaseOrders.project', 'purchaseOrders.items']);
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor
     */
    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:vendors,code,' . $vendor->id,
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'tax_office' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vendor->update($validated);

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Tedarikçi başarıyla güncellendi.');
    }

    /**
     * Remove the specified vendor
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Tedarikçi başarıyla silindi.');
    }
}
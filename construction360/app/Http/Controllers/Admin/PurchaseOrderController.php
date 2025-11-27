<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Services\PurchaseOrderNumberGeneratorService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    protected $numberGenerator;

    public function __construct(PurchaseOrderNumberGeneratorService $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Display a listing of purchase orders
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::query()->with(['vendor', 'project', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $purchaseOrders = $query->latest()->paginate(20);
        $vendors = Vendor::active()->get();
        $projects = Project::active()->get();

        return view('admin.purchase-orders.index', compact('purchaseOrders', 'vendors', 'projects'));
    }

    /**
     * Show the form for creating a new purchase order
     */
    public function create(Request $request)
    {
        $vendor = null;
        if ($request->has('vendor_id')) {
            $vendor = Vendor::findOrFail($request->vendor_id);
        }

        $vendors = Vendor::active()->get();
        $projects = Project::active()->get();
        $materials = Material::all();

        return view('admin.purchase-orders.create', compact('vendor', 'vendors', 'projects', 'materials'));
    }

    /**
     * Store a newly created purchase order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'delivery_address' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'nullable|exists:materials,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $poNumber = $this->numberGenerator->generate();

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        $taxRate = 18; // TODO: Get from settings
        $taxAmount = $subtotal * ($taxRate / 100);
        $totalAmount = $subtotal + $taxAmount;

        $po = PurchaseOrder::create([
            'po_number' => $poNumber,
            'vendor_id' => $validated['vendor_id'],
            'project_id' => $validated['project_id'] ?? null,
            'order_date' => $validated['order_date'],
            'delivery_date' => $validated['delivery_date'] ?? null,
            'delivery_address' => $validated['delivery_address'] ?? null,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency' => $validated['currency'] ?? 'TRY',
            'status' => 'draft',
            'terms' => $validated['terms'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Create items
        foreach ($validated['items'] as $index => $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'material_id' => $item['material_id'] ?? null,
                'description' => $item['description'],
                'unit' => $item['unit'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('admin.purchase-orders.show', $po)
            ->with('success', 'Satınalma siparişi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified purchase order
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['vendor', 'project', 'items.material', 'creator']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'draft') {
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Sadece taslak durumundaki siparişler düzenlenebilir.');
        }

        $purchaseOrder->load('items');
        $vendors = Vendor::active()->get();
        $projects = Project::active()->get();
        $materials = Material::all();

        return view('admin.purchase-orders.edit', compact('purchaseOrder', 'vendors', 'projects', 'materials'));
    }

    /**
     * Update the specified purchase order
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'draft') {
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Sadece taslak durumundaki siparişler düzenlenebilir.');
        }

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'delivery_address' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'nullable|exists:materials,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        $taxRate = 18; // TODO: Get from settings
        $taxAmount = $subtotal * ($taxRate / 100);
        $totalAmount = $subtotal + $taxAmount;

        $purchaseOrder->update([
            'vendor_id' => $validated['vendor_id'],
            'project_id' => $validated['project_id'] ?? null,
            'order_date' => $validated['order_date'],
            'delivery_date' => $validated['delivery_date'] ?? null,
            'delivery_address' => $validated['delivery_address'] ?? null,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency' => $validated['currency'] ?? 'TRY',
            'terms' => $validated['terms'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Delete existing items
        $purchaseOrder->items()->delete();

        // Create new items
        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'material_id' => $item['material_id'] ?? null,
                'description' => $item['description'],
                'unit' => $item['unit'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Satınalma siparişi başarıyla güncellendi.');
    }

    /**
     * Remove the specified purchase order
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'draft') {
            return redirect()->route('admin.purchase-orders.index')
                ->with('error', 'Sadece taslak durumundaki siparişler silinebilir.');
        }

        $purchaseOrder->delete();

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Satınalma siparişi başarıyla silindi.');
    }

    /**
     * Send purchase order to vendor
     */
    public function send(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'sent']);

        // TODO: Send email to vendor

        return back()->with('success', 'Satınalma siparişi tedarikçiye gönderildi.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuoteRequest;
use App\Http\Requests\Admin\UpdateQuoteRequest;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\QuoteCalculationService;
use App\Services\QuoteNumberGeneratorService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    protected $numberGenerator;
    protected $calculationService;

    public function __construct(
        QuoteNumberGeneratorService $numberGenerator,
        QuoteCalculationService $calculationService
    ) {
        $this->numberGenerator = $numberGenerator;
        $this->calculationService = $calculationService;
    }

    /**
     * Display a listing of quotes
     */
    public function index(Request $request)
    {
        $query = Quote::query()->with(['lead', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $quotes = $query->latest()->paginate(20);
        $leads = Lead::whereIn('status', ['contacted', 'site_visit_planned'])->get();

        return view('admin.quotes.index', compact('quotes', 'leads'));
    }

    /**
     * Show the form for creating a new quote
     */
    public function create(Request $request)
    {
        $lead = null;
        if ($request->has('lead_id')) {
            $lead = Lead::findOrFail($request->lead_id);
        }

        $leads = Lead::whereIn('status', ['contacted', 'site_visit_planned', 'quoted'])->get();

        return view('admin.quotes.create', compact('lead', 'leads'));
    }

    /**
     * Store a newly created quote
     */
    public function store(StoreQuoteRequest $request)
    {
        $quoteNumber = $this->numberGenerator->generate();

        // Create quote
        $quote = Quote::create([
            'quote_number' => $quoteNumber,
            'lead_id' => $request->lead_id,
            'version' => 1,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'title' => $request->title,
            'description' => $request->description,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'tax_percentage' => $request->tax_percentage ?? 18,
            'currency' => $request->currency ?? 'TRY',
            'valid_until' => $request->valid_until,
            'terms' => $request->terms,
            'notes' => $request->notes,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Create quote items
        if ($request->has('items')) {
            foreach ($request->items as $index => $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'sort_order' => $index,
                    'code' => $item['code'] ?? null,
                    'description' => $item['description'],
                    'unit' => $item['unit'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'category' => $item['category'] ?? null,
                ]);
            }
        }

        // Calculate totals
        $quote->load('items');
        $this->calculationService->recalculateAll($quote);

        // Update lead status if linked
        if ($quote->lead_id) {
            $quote->lead->update([
                'status' => 'quoted',
                'quoted_at' => now(),
            ]);
        }

        return redirect()->route('admin.quotes.show', $quote)
            ->with('success', 'Teklif başarıyla oluşturuldu.');
    }

    /**
     * Display the specified quote
     */
    public function show(Quote $quote)
    {
        $quote->load(['items', 'lead', 'creator', 'project']);
        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified quote
     */
    public function edit(Quote $quote)
    {
        $quote->load('items');
        $leads = Lead::whereIn('status', ['contacted', 'site_visit_planned', 'quoted'])->get();
        return view('admin.quotes.edit', compact('quote', 'leads'));
    }

    /**
     * Update the specified quote
     */
    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        // Update quote
        $quote->update([
            'title' => $request->title,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'description' => $request->description,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'tax_percentage' => $request->tax_percentage ?? 18,
            'currency' => $request->currency ?? 'TRY',
            'valid_until' => $request->valid_until,
            'status' => $request->status ?? $quote->status,
            'terms' => $request->terms,
            'notes' => $request->notes,
        ]);

        // Update or create items
        if ($request->has('items')) {
            // Delete existing items
            $quote->items()->delete();

            // Create new items
            foreach ($request->items as $index => $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'sort_order' => $index,
                    'code' => $item['code'] ?? null,
                    'description' => $item['description'],
                    'unit' => $item['unit'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'category' => $item['category'] ?? null,
                ]);
            }
        }

        // Recalculate totals
        $quote->load('items');
        $this->calculationService->recalculateAll($quote);

        return redirect()->route('admin.quotes.show', $quote)
            ->with('success', 'Teklif başarıyla güncellendi.');
    }

    /**
     * Remove the specified quote
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Teklif başarıyla silindi.');
    }

    /**
     * Send quote to client
     */
    public function send(Quote $quote)
    {
        $quote->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // TODO: Send email to client

        return back()->with('success', 'Teklif müşteriye gönderildi.');
    }

    /**
     * Create new version of quote
     */
    public function duplicate(Quote $quote)
    {
        $newQuote = $quote->replicate();
        $newQuote->quote_number = $this->numberGenerator->generate();
        $newQuote->version = $quote->version + 1;
        $newQuote->status = 'draft';
        $newQuote->sent_at = null;
        $newQuote->approved_at = null;
        $newQuote->save();

        // Duplicate items
        foreach ($quote->items as $item) {
            $newItem = $item->replicate();
            $newItem->quote_id = $newQuote->id;
            $newItem->save();
        }

        return redirect()->route('admin.quotes.edit', $newQuote)
            ->with('success', 'Yeni versiyon oluşturuldu.');
    }
}
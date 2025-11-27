<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Quote;
use App\Services\ContractNumberGeneratorService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    protected $numberGenerator;

    public function __construct(ContractNumberGeneratorService $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Display a listing of contracts
     */
    public function index(Request $request)
    {
        $query = Contract::query()->with(['project', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $contracts = $query->latest()->paginate(20);
        $projects = Project::active()->get();

        return view('admin.contracts.index', compact('contracts', 'projects'));
    }

    /**
     * Show the form for creating a new contract
     */
    public function create(Request $request)
    {
        $project = null;
        $quote = null;

        if ($request->has('quote_id')) {
            $quote = Quote::with('lead', 'project')->findOrFail($request->quote_id);
            $project = $quote->project;
        } elseif ($request->has('project_id')) {
            $project = Project::with('quote')->findOrFail($request->project_id);
            $quote = $project->quote;
        }

        $projects = Project::active()->whereDoesntHave('contract')->get();

        return view('admin.contracts.create', compact('project', 'quote', 'projects'));
    }

    /**
     * Store a newly created contract
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'contract_amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'retention_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'contract_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'terms' => 'nullable|string',
            'template_content' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $contractNumber = $this->numberGenerator->generate();

        $contract = Contract::create([
            'contract_number' => $contractNumber,
            'project_id' => $validated['project_id'],
            'version' => 1,
            'title' => $validated['title'],
            'contract_amount' => $validated['contract_amount'],
            'advance_amount' => $validated['advance_amount'] ?? 0,
            'retention_amount' => $validated['retention_amount'] ?? 0,
            'currency' => $validated['currency'] ?? 'TRY',
            'contract_date' => $validated['contract_date'] ?? now(),
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'template_content' => $validated['template_content'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Update project with contract_id
        $project = Project::find($validated['project_id']);
        $project->update([
            'contract_id' => $contract->id,
            'contract_amount' => $contract->contract_amount,
            'status' => 'planned',
        ]);

        // Update quote status if linked
        if ($project->quote_id) {
            $project->quote->update(['status' => 'contracted']);
        }

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', 'Sözleşme başarıyla oluşturuldu.');
    }

    /**
     * Display the specified contract
     */
    public function show(Contract $contract)
    {
        $contract->load(['project.quote', 'project.client', 'creator']);
        return view('admin.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified contract
     */
    public function edit(Contract $contract)
    {
        $contract->load('project');
        return view('admin.contracts.edit', compact('contract'));
    }

    /**
     * Update the specified contract
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'contract_amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'retention_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'contract_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'terms' => 'nullable|string',
            'template_content' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $contract->update($validated);

        // Update project contract amount if changed
        if ($contract->project && $contract->contract_amount != $contract->project->contract_amount) {
            $contract->project->update([
                'contract_amount' => $contract->contract_amount,
            ]);
        }

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', 'Sözleşme başarıyla güncellendi.');
    }

    /**
     * Remove the specified contract
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Sözleşme başarıyla silindi.');
    }
}
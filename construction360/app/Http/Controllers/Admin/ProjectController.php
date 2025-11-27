<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Quote;
use App\Models\Site;
use App\Models\User;
use App\Services\ProjectNumberGeneratorService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $numberGenerator;

    public function __construct(ProjectNumberGeneratorService $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::query()->with(['lead', 'quote', 'contract', 'site', 'client']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('location_city', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_type')) {
            $query->where('project_type', $request->project_type);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $projects = $query->latest()->paginate(20);
        $clients = User::where('role', 'client')->get();

        return view('admin.projects.index', compact('projects', 'clients'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create(Request $request)
    {
        $quote = null;
        if ($request->has('quote_id')) {
            $quote = Quote::with('lead')->findOrFail($request->quote_id);
        }

        $quotes = Quote::where('status', 'approved')->with('lead')->get();
        $sites = Site::active()->get();
        $clients = User::where('role', 'client')->get();

        return view('admin.projects.create', compact('quote', 'quotes', 'sites', 'clients'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lead_id' => 'nullable|exists:leads,id',
            'quote_id' => 'nullable|exists:quotes,id',
            'site_id' => 'nullable|exists:sites,id',
            'client_id' => 'nullable|exists:users,id',
            'project_type' => 'required|in:konut,ticari,endustriyel,tadilat,diger',
            'description' => 'nullable|string',
            'area_m2' => 'nullable|numeric|min:0',
            'location_city' => 'nullable|string|max:255',
            'location_district' => 'nullable|string|max:255',
            'location_address' => 'nullable|string',
            'start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after:start_date',
            'contract_amount' => 'nullable|numeric|min:0',
            'budget_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string',
        ]);

        $projectCode = $this->numberGenerator->generate();

        $project = Project::create([
            'project_code' => $projectCode,
            'name' => $validated['name'],
            'lead_id' => $validated['lead_id'] ?? null,
            'quote_id' => $validated['quote_id'] ?? null,
            'site_id' => $validated['site_id'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'project_type' => $validated['project_type'],
            'description' => $validated['description'] ?? null,
            'area_m2' => $validated['area_m2'] ?? null,
            'location_city' => $validated['location_city'] ?? null,
            'location_district' => $validated['location_district'] ?? null,
            'location_address' => $validated['location_address'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'planned_end_date' => $validated['planned_end_date'] ?? null,
            'contract_amount' => $validated['contract_amount'] ?? 0,
            'budget_amount' => $validated['budget_amount'] ?? 0,
            'currency' => $validated['currency'] ?? 'TRY',
            'status' => 'planned',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update quote status if linked
        if ($project->quote_id) {
            $project->quote->update(['status' => 'contracted']);
        }

        // Update lead status if linked
        if ($project->lead_id) {
            $project->lead->update(['status' => 'won']);
        }

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Proje başarıyla oluşturuldu.');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load(['lead', 'quote', 'contract', 'site', 'client']);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        $sites = Site::active()->get();
        $clients = User::where('role', 'client')->get();
        return view('admin.projects.edit', compact('project', 'sites', 'clients'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'site_id' => 'nullable|exists:sites,id',
            'client_id' => 'nullable|exists:users,id',
            'project_type' => 'required|in:konut,ticari,endustriyel,tadilat,diger',
            'description' => 'nullable|string',
            'area_m2' => 'nullable|numeric|min:0',
            'location_city' => 'nullable|string|max:255',
            'location_district' => 'nullable|string|max:255',
            'location_address' => 'nullable|string',
            'start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'contract_amount' => 'nullable|numeric|min:0',
            'budget_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:planned,in_progress,on_hold,completed,handed_over,cancelled',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Proje başarıyla güncellendi.');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proje başarıyla silindi.');
    }
}
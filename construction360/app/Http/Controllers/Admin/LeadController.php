<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadRequest;
use App\Http\Requests\Admin\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadNumberGeneratorService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    protected $numberGenerator;

    public function __construct(LeadNumberGeneratorService $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Display a listing of leads
     */
    public function index(Request $request)
    {
        $query = Lead::query()->with(['assignedUser']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('lead_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_type')) {
            $query->where('project_type', $request->project_type);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('location_city')) {
            $query->where('location_city', $request->location_city);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->latest()->paginate(20);

        $users = User::whereIn('role', ['admin', 'project_manager'])->get();

        return view('admin.leads.index', compact('leads', 'users'));
    }

    /**
     * Show the form for creating a new lead
     */
    public function create()
    {
        $users = User::whereIn('role', ['admin', 'project_manager'])->get();
        return view('admin.leads.create', compact('users'));
    }

    /**
     * Store a newly created lead
     */
    public function store(StoreLeadRequest $request)
    {
        $leadNumber = $this->numberGenerator->generate();

        $lead = Lead::create([
            'lead_number' => $leadNumber,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'company' => $request->company,
            'project_type' => $request->project_type,
            'location_city' => $request->location_city,
            'location_district' => $request->location_district,
            'location_address' => $request->location_address,
            'area_m2' => $request->area_m2,
            'room_count' => $request->room_count,
            'floor_count' => $request->floor_count,
            'current_status' => $request->current_status,
            'requested_services' => $request->requested_services,
            'budget_range' => $request->budget_range,
            'source' => $request->source ?? 'web',
            'message' => $request->message,
            'requested_site_visit_date' => $request->requested_site_visit_date,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'notes' => $request->notes,
            'kvkk_consent' => true,
        ]);

        return redirect()->route('admin.leads.show', $lead)
            ->with('success', 'Lead başarıyla oluşturuldu.');
    }

    /**
     * Display the specified lead
     */
    public function show(Lead $lead)
    {
        $lead->load(['assignedUser', 'quotes', 'project']);
        $users = User::whereIn('role', ['admin', 'project_manager'])->get();
        
        return view('admin.leads.show', compact('lead', 'users'));
    }

    /**
     * Show the form for editing the specified lead
     */
    public function edit(Lead $lead)
    {
        $users = User::whereIn('role', ['admin', 'project_manager'])->get();
        return view('admin.leads.edit', compact('lead', 'users'));
    }

    /**
     * Update the specified lead
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $data = $request->validated();
        
        // Update status timestamps
        if ($request->status == 'contacted' && !$lead->contacted_at) {
            $data['contacted_at'] = now();
        }
        
        if ($request->status == 'site_visit_planned' && !$lead->site_visit_at) {
            $data['site_visit_at'] = $request->site_visit_at ?? now();
        }
        
        if ($request->status == 'quoted' && !$lead->quoted_at) {
            $data['quoted_at'] = now();
        }

        $lead->update($data);

        return redirect()->route('admin.leads.show', $lead)
            ->with('success', 'Lead başarıyla güncellendi.');
    }

    /**
     * Remove the specified lead
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead başarıyla silindi.');
    }
}
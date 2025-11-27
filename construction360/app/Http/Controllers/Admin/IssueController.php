<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use App\Services\IssueNumberGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    protected $numberGenerator;

    public function __construct(IssueNumberGeneratorService $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Display a listing of issues
     */
    public function index(Request $request)
    {
        $query = Issue::query()->with(['project', 'creator', 'assignee']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issue_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Show overdue issues first
        if ($request->has('overdue')) {
            $query->overdue();
        }

        $issues = $query->latest()->paginate(20);
        $projects = Project::active()->get();
        $users = User::whereIn('role', ['admin', 'project_manager', 'designer'])->get();

        return view('admin.issues.index', compact('issues', 'projects', 'users'));
    }

    /**
     * Show the form for creating a new issue
     */
    public function create(Request $request)
    {
        $project = null;
        if ($request->has('project_id')) {
            $project = Project::findOrFail($request->project_id);
        }

        $projects = Project::active()->get();
        $users = User::whereIn('role', ['admin', 'project_manager', 'designer', 'site_supervisor'])->get();

        return view('admin.issues.create', compact('project', 'projects', 'users'));
    }

    /**
     * Store a newly created issue
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:kalite,is_guvenligi,tedarik,tasarim,musteri,diger',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'nullable|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:10240',
        ]);

        $issueNumber = $this->numberGenerator->generate();

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('issues/photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $issue = Issue::create([
            'issue_number' => $issueNumber,
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => $validated['status'] ?? 'open',
            'assigned_to' => $validated['assigned_to'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'photos' => !empty($photoPaths) ? $photoPaths : null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.issues.show', $issue)
            ->with('success', 'Issue başarıyla oluşturuldu.');
    }

    /**
     * Display the specified issue
     */
    public function show(Issue $issue)
    {
        $issue->load(['project', 'creator', 'assignee', 'comments.creator']);
        return view('admin.issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified issue
     */
    public function edit(Issue $issue)
    {
        $projects = Project::active()->get();
        $users = User::whereIn('role', ['admin', 'project_manager', 'designer', 'site_supervisor'])->get();
        return view('admin.issues.edit', compact('issue', 'projects', 'users'));
    }

    /**
     * Update the specified issue
     */
    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:kalite,is_guvenligi,tedarik,tasarim,musteri,diger',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'resolution' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:10240',
        ]);

        // Handle photo uploads
        $photoPaths = $issue->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('issues/photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Update resolution date if status changed to resolved
        $updateData = $validated;
        $updateData['photos'] = !empty($photoPaths) ? $photoPaths : null;

        if ($validated['status'] == 'resolved' && $issue->status != 'resolved') {
            $updateData['resolved_at'] = now();
        }

        if ($validated['status'] == 'closed' && $issue->status != 'closed') {
            $updateData['closed_at'] = now();
        }

        $issue->update($updateData);

        return redirect()->route('admin.issues.show', $issue)
            ->with('success', 'Issue başarıyla güncellendi.');
    }

    /**
     * Remove the specified issue
     */
    public function destroy(Issue $issue)
    {
        // Delete photos
        if ($issue->photos) {
            foreach ($issue->photos as $photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
        }

        $issue->delete();

        return redirect()->route('admin.issues.index')
            ->with('success', 'Issue başarıyla silindi.');
    }
}
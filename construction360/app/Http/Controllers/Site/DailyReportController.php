<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\DailySiteReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DailyReportController extends Controller
{
    /**
     * Show the form for creating a daily report
     */
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // Check if user has access to this project
        // TODO: Add proper authorization check
        
        return view('site.daily-report.create', compact('project'));
    }

    /**
     * Store a newly created daily report
     */
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        $validated = $request->validate([
            'report_date' => 'required|date',
            'weather' => 'nullable|string|max:255',
            'work_start_time' => 'nullable',
            'work_end_time' => 'nullable',
            'team_count' => 'nullable|integer|min:0',
            'subcontractor_count' => 'nullable|integer|min:0',
            'work_areas' => 'nullable|string',
            'work_items' => 'nullable|array',
            'materials_used' => 'nullable|array',
            'summary' => 'nullable|string',
            'obstacles' => 'nullable|string',
            'safety_checklist' => 'nullable|array',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:10240', // 10MB max per photo
        ]);

        $report = DailySiteReport::create([
            'project_id' => $projectId,
            'created_by' => Auth::id(),
            'report_date' => $validated['report_date'],
            'weather' => $validated['weather'] ?? null,
            'work_start_time' => $validated['work_start_time'] ?? null,
            'work_end_time' => $validated['work_end_time'] ?? null,
            'team_count' => $validated['team_count'] ?? 0,
            'subcontractor_count' => $validated['subcontractor_count'] ?? 0,
            'work_areas' => $validated['work_areas'] ?? null,
            'work_items' => $validated['work_items'] ?? null,
            'materials_used' => $validated['materials_used'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'obstacles' => $validated['obstacles'] ?? null,
            'safety_checklist' => $validated['safety_checklist'] ?? null,
            'status' => 'submitted',
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('site-reports/photos', 'public');
                \App\Models\SiteReportPhoto::create([
                    'daily_site_report_id' => $report->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'file_size' => $photo->getSize(),
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('site.dashboard')
            ->with('success', 'Günlük rapor başarıyla gönderildi.');
    }
}
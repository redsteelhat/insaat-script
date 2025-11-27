<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of assigned projects
     */
    public function index()
    {
        $projects = Project::whereIn('status', ['planned', 'in_progress'])
            ->latest()
            ->paginate(10);
        
        return view('site.projects.index', compact('projects'));
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load(['site', 'client']);
        
        // Get recent daily reports
        $recentReports = $project->dailySiteReports()
            ->latest()
            ->take(5)
            ->get();
        
        return view('site.projects.show', compact('project', 'recentReports'));
    }
}
<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the site dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get assigned projects (for site supervisor, projects assigned to them)
        $projects = Project::whereIn('status', ['planned', 'in_progress'])
            ->where('site_id', '!=', null) // Has a site assigned
            ->latest()
            ->take(5)
            ->get();
        
        // Get open issues
        $openIssues = Issue::whereIn('status', ['open', 'in_progress'])
            ->whereHas('project', function ($query) {
                // Only projects with assigned site
            })
            ->latest()
            ->take(5)
            ->get();
        
        return view('site.dashboard', compact('projects', 'openIssues'));
    }
}
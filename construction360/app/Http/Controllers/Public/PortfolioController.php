<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display a listing of portfolio projects
     */
    public function index(Request $request)
    {
        // TODO: Get portfolio projects from database when portfolio_projects table is created
        $filters = [
            'project_type' => $request->project_type,
            'year' => $request->year,
            'location' => $request->location,
        ];
        
        return view('public.portfolio.index', compact('filters'));
    }

    /**
     * Display the specified portfolio project
     */
    public function show(string $slug)
    {
        // TODO: Get portfolio project from database by slug
        return view('public.portfolio.show', ['slug' => $slug]);
    }
}
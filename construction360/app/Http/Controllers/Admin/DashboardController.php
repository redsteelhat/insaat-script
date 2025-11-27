<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with KPIs and charts
     */
    public function index(Request $request)
    {
        // Date filter
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->endOfMonth()->format('Y-m-d');

        // Lead Pipeline KPIs
        $newLeads = Lead::where('status', 'new')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();
        
        $activeQuotes = Quote::whereIn('status', ['draft', 'sent'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();
        
        $wonLeads = Lead::where('status', 'won')
            ->whereBetween('quoted_at', [$dateFrom, $dateTo])
            ->count();
        
        $lostLeads = Lead::where('status', 'lost')
            ->whereBetween('quoted_at', [$dateFrom, $dateTo])
            ->count();

        // Project KPIs
        $activeProjects = Project::whereIn('status', ['planned', 'in_progress'])->count();
        $delayedProjects = Project::whereIn('status', ['planned', 'in_progress'])
            ->where('planned_end_date', '<', now())
            ->count();
        $completedProjects = Project::whereIn('status', ['completed', 'handed_over'])->count();

        // Financial KPIs (Example - will need actual invoice/payment data)
        $totalRevenue = Project::whereIn('status', ['completed', 'handed_over'])
            ->sum('contract_amount');
        
        $totalCost = Project::sum('actual_cost');
        
        $accountsReceivable = $totalRevenue - $totalCost; // Simplified

        // Budget Variance
        $budgetVariance = Project::select(
                DB::raw('SUM(budget_amount) as total_budget'),
                DB::raw('SUM(actual_cost) as total_cost')
            )
            ->where('budget_amount', '>', 0)
            ->first();
        
        $variance = $budgetVariance->total_budget - $budgetVariance->total_cost;

        // Monthly Charts Data
        $monthlyLeads = Lead::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [now()->subMonths(11)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyQuotes = Quote::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [now()->subMonths(11)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Project Status Distribution
        $projectStatusDistribution = Project::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'newLeads',
            'activeQuotes',
            'wonLeads',
            'lostLeads',
            'activeProjects',
            'delayedProjects',
            'completedProjects',
            'totalRevenue',
            'totalCost',
            'accountsReceivable',
            'variance',
            'monthlyLeads',
            'monthlyQuotes',
            'projectStatusDistribution',
            'dateFrom',
            'dateTo'
        ));
    }
}
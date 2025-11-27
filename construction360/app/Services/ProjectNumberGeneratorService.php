<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;

class ProjectNumberGeneratorService
{
    /**
     * Generate a unique project code in format: PRJ-YYYY-XXXX
     */
    public function generate(): string
    {
        $year = Carbon::now()->year;
        $prefix = "PRJ-{$year}-";
        
        // Get the last project code for this year
        $lastProject = Project::where('project_code', 'like', "{$prefix}%")
            ->orderBy('project_code', 'desc')
            ->first();
        
        if ($lastProject) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastProject->project_code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First project of the year
            $newNumber = '0001';
        }
        
        return "{$prefix}{$newNumber}";
    }
}

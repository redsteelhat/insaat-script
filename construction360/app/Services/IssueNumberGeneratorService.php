<?php

namespace App\Services;

use App\Models\Issue;

class IssueNumberGeneratorService
{
    /**
     * Generate a unique issue number
     * Format: ISS-YYYY-XXXX
     */
    public function generate(): string
    {
        $year = date('Y');
        $prefix = "ISS-{$year}-";
        
        // Get the last issue number for this year
        $lastIssue = Issue::where('issue_number', 'like', "{$prefix}%")
            ->orderBy('issue_number', 'desc')
            ->first();
        
        if ($lastIssue) {
            // Extract the number part
            $lastNumber = (int) substr($lastIssue->issue_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

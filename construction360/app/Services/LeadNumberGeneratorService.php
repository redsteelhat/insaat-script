<?php

namespace App\Services;

use App\Models\Lead;
use Carbon\Carbon;

class LeadNumberGeneratorService
{
    /**
     * Generate a unique lead number in format: TAL-YYYY-XXXX
     */
    public function generate(): string
    {
        $year = Carbon::now()->year;
        $prefix = "TAL-{$year}-";
        
        // Get the last lead number for this year
        $lastLead = Lead::where('lead_number', 'like', "{$prefix}%")
            ->orderBy('lead_number', 'desc')
            ->first();
        
        if ($lastLead) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastLead->lead_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First lead of the year
            $newNumber = '0001';
        }
        
        return "{$prefix}{$newNumber}";
    }
}

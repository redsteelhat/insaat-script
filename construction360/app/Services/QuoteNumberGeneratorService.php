<?php

namespace App\Services;

use App\Models\Quote;
use Carbon\Carbon;

class QuoteNumberGeneratorService
{
    /**
     * Generate a unique quote number in format: TEK-YYYY-XXXX
     */
    public function generate(): string
    {
        $year = Carbon::now()->year;
        $prefix = "TEK-{$year}-";
        
        // Get the last quote number for this year
        $lastQuote = Quote::where('quote_number', 'like', "{$prefix}%")
            ->orderBy('quote_number', 'desc')
            ->first();
        
        if ($lastQuote) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastQuote->quote_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First quote of the year
            $newNumber = '0001';
        }
        
        return "{$prefix}{$newNumber}";
    }
}

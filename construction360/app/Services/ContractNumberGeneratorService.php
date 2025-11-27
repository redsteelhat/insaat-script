<?php

namespace App\Services;

use App\Models\Contract;

class ContractNumberGeneratorService
{
    /**
     * Generate a unique contract number
     * Format: SOZ-YYYY-XXXX
     */
    public function generate(): string
    {
        $year = date('Y');
        $prefix = "SOZ-{$year}-";
        
        // Get the last contract number for this year
        $lastContract = Contract::where('contract_number', 'like', "{$prefix}%")
            ->orderBy('contract_number', 'desc')
            ->first();
        
        if ($lastContract) {
            // Extract the number part
            $lastNumber = (int) substr($lastContract->contract_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

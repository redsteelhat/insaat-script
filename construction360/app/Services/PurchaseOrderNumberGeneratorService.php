<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PurchaseOrderNumberGeneratorService
{
    /**
     * Generate a unique purchase order number
     * Format: PO-YYYY-XXXX
     */
    public function generate(): string
    {
        $year = date('Y');
        $prefix = "PO-{$year}-";
        
        // Get the last PO number for this year
        $lastPO = PurchaseOrder::where('po_number', 'like', "{$prefix}%")
            ->orderBy('po_number', 'desc')
            ->first();
        
        if ($lastPO) {
            // Extract the number part
            $lastNumber = (int) substr($lastPO->po_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

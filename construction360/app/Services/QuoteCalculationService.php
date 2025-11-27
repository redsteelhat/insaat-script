<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\QuoteItem;

class QuoteCalculationService
{
    /**
     * Calculate and update quote totals
     */
    public function calculateTotals(Quote $quote): Quote
    {
        // Calculate subtotal from items
        $subtotal = $quote->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        
        // Calculate discount
        $discountPercentage = $quote->discount_percentage ?? 0;
        $discountAmount = $subtotal * ($discountPercentage / 100);
        $subtotalAfterDiscount = $subtotal - $discountAmount;
        
        // Calculate tax
        $taxPercentage = $quote->tax_percentage ?? 18; // Default %18 KDV
        $taxAmount = $subtotalAfterDiscount * ($taxPercentage / 100);
        
        // Calculate total
        $totalAmount = $subtotalAfterDiscount + $taxAmount;
        
        // Update quote
        $quote->update([
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total_amount' => round($totalAmount, 2),
        ]);
        
        return $quote->fresh();
    }
    
    /**
     * Calculate item totals
     */
    public function calculateItemTotals(QuoteItem $item): QuoteItem
    {
        $totalPrice = $item->quantity * $item->unit_price;
        
        $item->update([
            'total_price' => round($totalPrice, 2),
        ]);
        
        return $item;
    }
    
    /**
     * Recalculate all items and quote totals
     */
    public function recalculateAll(Quote $quote): Quote
    {
        // Recalculate all items
        $quote->items->each(function ($item) {
            $this->calculateItemTotals($item);
        });
        
        // Refresh quote relationship
        $quote->load('items');
        
        // Recalculate quote totals
        return $this->calculateTotals($quote);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'sort_order',
        'code',
        'description',
        'unit',
        'quantity',
        'unit_price',
        'material_cost',
        'labor_cost',
        'overhead_cost',
        'profit_margin',
        'total_price',
        'category',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Otomatik toplam hesaplama
            if ($item->quantity && $item->unit_price) {
                $item->total_price = $item->quantity * $item->unit_price;
            }
        });
    }
}
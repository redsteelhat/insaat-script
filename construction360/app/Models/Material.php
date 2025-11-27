<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit',
        'min_stock',
        'current_stock',
        'last_purchase_price',
        'average_cost',
        'is_active',
        'description',
        'notes',
    ];

    protected $casts = [
        'min_stock' => 'decimal:3',
        'current_stock' => 'decimal:3',
        'last_purchase_price' => 'decimal:2',
        'average_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'min_stock');
    }
}
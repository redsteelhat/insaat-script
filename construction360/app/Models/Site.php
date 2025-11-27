<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'city',
        'district',
        'postal_code',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
        'contact_email',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->address, $this->district, $this->city, $this->postal_code]);
        return implode(', ', $parts);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
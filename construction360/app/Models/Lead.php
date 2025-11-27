<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_number',
        'name',
        'phone',
        'email',
        'company',
        'project_type',
        'location_city',
        'location_district',
        'location_address',
        'area_m2',
        'room_count',
        'floor_count',
        'current_status',
        'requested_services',
        'budget_range',
        'source',
        'message',
        'requested_site_visit_date',
        'status',
        'assigned_to',
        'kvkk_consent',
        'notes',
        'contacted_at',
        'site_visit_at',
        'quoted_at',
    ];

    protected $casts = [
        'requested_services' => 'array',
        'kvkk_consent' => 'boolean',
        'area_m2' => 'decimal:2',
        'requested_site_visit_date' => 'date',
        'contacted_at' => 'datetime',
        'site_visit_at' => 'datetime',
        'quoted_at' => 'datetime',
    ];

    // Relationships
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    // Accessors
    public function getFullLocationAttribute()
    {
        $parts = array_filter([$this->location_city, $this->location_district, $this->location_address]);
        return implode(', ', $parts);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }
}
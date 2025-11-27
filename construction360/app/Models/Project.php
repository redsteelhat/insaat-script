<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_code',
        'name',
        'lead_id',
        'quote_id',
        'contract_id',
        'site_id',
        'client_id',
        'project_type',
        'description',
        'area_m2',
        'location_city',
        'location_district',
        'location_address',
        'start_date',
        'planned_end_date',
        'actual_end_date',
        'contract_amount',
        'budget_amount',
        'actual_cost',
        'currency',
        'status',
        'progress_percentage',
        'payment_schedule',
        'notes',
    ];

    protected $casts = [
        'area_m2' => 'decimal:2',
        'contract_amount' => 'decimal:2',
        'budget_amount' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'start_date' => 'date',
        'planned_end_date' => 'date',
        'actual_end_date' => 'date',
        'payment_schedule' => 'array',
        'progress_percentage' => 'integer',
    ];

    // Relationships
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['planned', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'handed_over']);
    }

    // Accessors
    public function getFullLocationAttribute()
    {
        $parts = array_filter([$this->location_city, $this->location_district, $this->location_address]);
        return implode(', ', $parts);
    }
}
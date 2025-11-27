<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySiteReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'created_by',
        'report_date',
        'weather',
        'work_start_time',
        'work_end_time',
        'team_count',
        'subcontractor_count',
        'work_areas',
        'work_items',
        'materials_used',
        'summary',
        'obstacles',
        'safety_checklist',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'report_date' => 'date',
        'work_start_time' => 'datetime',
        'work_end_time' => 'datetime',
        'work_items' => 'array',
        'materials_used' => 'array',
        'safety_checklist' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function photos()
    {
        return $this->hasMany(SiteReportPhoto::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
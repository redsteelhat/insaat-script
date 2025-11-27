<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number',
        'project_id',
        'version',
        'title',
        'terms',
        'template_content',
        'contract_amount',
        'advance_amount',
        'retention_amount',
        'currency',
        'contract_date',
        'start_date',
        'end_date',
        'signed_file_path',
        'signed_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'contract_amount' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'retention_amount' => 'decimal:2',
        'contract_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
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

    // Scopes
    public function scopeSigned($query)
    {
        return $query->whereNotNull('signed_at');
    }

    public function scopeUnsigned($query)
    {
        return $query->whereNull('signed_at');
    }
}
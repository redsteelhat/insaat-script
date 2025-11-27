<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteReportPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_site_report_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'caption',
        'sort_order',
    ];

    // Relationships
    public function dailySiteReport()
    {
        return $this->belongsTo(DailySiteReport::class);
    }

    // Accessors
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
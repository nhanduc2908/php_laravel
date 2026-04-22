<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $table = 'alerts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'type', 'severity', 'title', 'message', 'is_read', 'is_resolved',
        'resolved_at', 'resolved_by', 'source', 'metadata'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_resolved' => 'boolean',
        'metadata' => 'array',
        'resolved_at' => 'datetime'
    ];

    public function notes()
    {
        return $this->hasMany(AlertNote::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function getSeverityColor()
    {
        return match ($this->severity) {
            'critical' => '#dc3545',
            'high' => '#fd7e14',
            'medium' => '#ffc107',
            'low' => '#28a745',
            default => '#6c757d'
        };
    }
}
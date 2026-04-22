<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use SoftDeletes;

    protected $table = 'servers';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'ssh_key',
        'description', 'status', 'os_type', 'last_scan_at', 'last_connection_test'
    ];

    protected $casts = [
        'last_scan_at' => 'datetime',
        'last_connection_test' => 'datetime'
    ];

    public function assessments()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function reports()
    {
        return $this->hasMany(AssessmentReport::class);
    }

    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }

    public function files()
    {
        return $this->hasMany(AssessmentFile::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_server');
    }

    public function getCurrentScore()
    {
        $latestReport = $this->reports()->latest()->first();
        return $latestReport ? $latestReport->total_score : 0;
    }

    public function getComplianceStatus()
    {
        $total = $this->assessments()->count();
        $compliant = $this->assessments()->where('score', '>=', 70)->count();
        return $total > 0 ? round(($compliant / $total) * 100, 2) : 0;
    }
}
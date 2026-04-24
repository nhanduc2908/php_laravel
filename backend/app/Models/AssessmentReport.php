<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentReport extends Model
{
    protected $table = 'assessment_reports';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'server_id', 'total_score', 'compliance_percent', 'status',
        'summary', 'recommendations', 'created_by', 'completed_at'
    ];

    protected $casts = [
        'total_score' => 'float',
        'compliance_percent' => 'float',
        'completed_at' => 'datetime'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getScoreGrade()
    {
        if ($this->total_score >= 90) return 'A';
        if ($this->total_score >= 80) return 'B';
        if ($this->total_score >= 70) return 'C';
        if ($this->total_score >= 60) return 'D';
        return 'F';
    }
}
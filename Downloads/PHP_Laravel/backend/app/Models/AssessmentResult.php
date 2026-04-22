<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    protected $table = 'assessment_results';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'server_id', 'criteria_id', 'score', 'evidence', 'note', 'assessed_by'
    ];

    protected $casts = [
        'score' => 'float'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
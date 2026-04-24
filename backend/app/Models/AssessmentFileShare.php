<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentFileShare extends Model
{
    protected $table = 'assessment_file_shares';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'file_id', 'shared_with', 'permission', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function file()
    {
        return $this->belongsTo(AssessmentFile::class, 'file_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentFileVersion extends Model
{
    protected $table = 'assessment_file_versions';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'file_id', 'version', 'content', 'created_by'
    ];

    public function file()
    {
        return $this->belongsTo(AssessmentFile::class, 'file_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
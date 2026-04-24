<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentFile extends Model
{
    use SoftDeletes;

    protected $table = 'assessment_files';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'title', 'content', 'server_id', 'created_by', 'status',
        'version', 'file_path', 'file_type', 'file_size', 'tags', 'due_date', 'priority'
    ];

    protected $casts = [
        'tags' => 'array',
        'due_date' => 'date',
        'file_size' => 'integer'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function shares()
    {
        return $this->hasMany(AssessmentFileShare::class);
    }

    public function versions()
    {
        return $this->hasMany(AssessmentFileVersion::class)->orderBy('version', 'desc');
    }

    public function isSharedWith($userId)
    {
        return $this->shares()->where('shared_with', $userId)->exists();
    }

    public function getCurrentVersion()
    {
        return $this->versions()->first();
    }

    public function createNewVersion($content, $userId)
    {
        $newVersion = $this->version + 1;
        return $this->versions()->create([
            'version' => $newVersion,
            'content' => $content,
            'created_by' => $userId
        ]);
    }
}
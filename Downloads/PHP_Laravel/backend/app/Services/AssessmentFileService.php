<?php

namespace App\Services;

use App\Models\AssessmentFile;
use App\Models\AssessmentFileShare;
use App\Models\AssessmentFileVersion;
use App\Events\FileShared;
use Illuminate\Support\Facades\Auth;

class AssessmentFileService
{
    protected $uploadService;
    
    public function __construct(FileUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    
    public function create($data)
    {
        $data['created_by'] = Auth::id();
        $data['version'] = 1;
        
        $file = AssessmentFile::create($data);
        
        // Create initial version
        $this->createVersion($file->id, $data['content'], Auth::id());
        
        return $file;
    }
    
    public function update($id, $data)
    {
        $file = AssessmentFile::findOrFail($id);
        
        // Check if content changed
        if (isset($data['content']) && $data['content'] != $file->content) {
            $this->createVersion($id, $data['content'], Auth::id());
            $data['version'] = $file->version + 1;
        }
        
        $file->update($data);
        
        return $file;
    }
    
    public function delete($id)
    {
        $file = AssessmentFile::findOrFail($id);
        
        // Delete all versions
        $file->versions()->delete();
        
        // Delete all shares
        $file->shares()->delete();
        
        // Delete file
        $file->delete();
        
        return true;
    }
    
    public function share($fileId, $userId, $permission = 'view')
    {
        $share = AssessmentFileShare::create([
            'file_id' => $fileId,
            'shared_with' => $userId,
            'permission' => $permission,
            'expires_at' => now()->addDays(30)
        ]);
        
        event(new FileShared($share));
        
        return $share;
    }
    
    public function getVersions($fileId)
    {
        return AssessmentFileVersion::where('file_id', $fileId)
            ->with('creator')
            ->orderBy('version', 'desc')
            ->get();
    }
    
    public function createVersion($fileId, $content, $userId)
    {
        $latestVersion = AssessmentFileVersion::where('file_id', $fileId)
            ->max('version') ?? 0;
        
        return AssessmentFileVersion::create([
            'file_id' => $fileId,
            'version' => $latestVersion + 1,
            'content' => $content,
            'created_by' => $userId
        ]);
    }
    
    public function restoreVersion($fileId, $versionId)
    {
        $version = AssessmentFileVersion::findOrFail($versionId);
        $file = AssessmentFile::findOrFail($fileId);
        
        $file->update([
            'content' => $version->content,
            'version' => $file->version + 1
        ]);
        
        $this->createVersion($fileId, $version->content, Auth::id());
        
        return $file;
    }
    
    public function uploadAttachment($fileId, $attachment)
    {
        $file = AssessmentFile::findOrFail($fileId);
        $uploaded = $this->uploadService->upload($attachment, "files/{$fileId}/attachments");
        
        return $uploaded;
    }
    
    public function download($fileId)
    {
        $file = AssessmentFile::findOrFail($fileId);
        
        // Log download
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'download_file',
            'resource' => 'assessment_file',
            'resource_id' => $fileId
        ]);
        
        return response()->download(storage_path("app/{$file->file_path}"));
    }
}

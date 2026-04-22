<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    protected $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 
        'xls', 'xlsx', 'csv', 'txt', 'zip', 'rar'
    ];
    
    protected $maxSize = 10485760; // 10MB
    
    public function upload(UploadedFile $file, $path = 'uploads', $disk = 'public')
    {
        $this->validateFile($file);
        
        $filename = $this->generateFilename($file);
        $fullPath = $file->storeAs($path, $filename, $disk);
        
        return [
            'original_name' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $fullPath,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'disk' => $disk
        ];
    }
    
    public function uploadMultiple($files, $path = 'uploads', $disk = 'public')
    {
        $uploaded = [];
        
        foreach ($files as $file) {
            $uploaded[] = $this->upload($file, $path, $disk);
        }
        
        return $uploaded;
    }
    
    public function delete($path, $disk = 'public')
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
    
    public function getUrl($path, $disk = 'public')
    {
        return Storage::disk($disk)->url($path);
    }
    
    protected function validateFile(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \Exception("File type {$extension} not allowed");
        }
        
        if ($file->getSize() > $this->maxSize) {
            throw new \Exception("File size exceeds limit of " . ($this->maxSize / 1048576) . "MB");
        }
        
        return true;
    }
    
    protected function generateFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $random = Str::random(40);
        $timestamp = now()->format('Ymd_His');
        
        return "{$timestamp}_{$random}.{$extension}";
    }
    
    public function uploadAvatar(UploadedFile $file, $userId)
    {
        $path = "avatars/{$userId}";
        return $this->upload($file, $path, 'public');
    }
    
    public function uploadEvidence(UploadedFile $file, $assessmentId)
    {
        $path = "evidence/{$assessmentId}";
        return $this->upload($file, $path, 'public');
    }
    
    public function uploadReport(UploadedFile $file, $reportId)
    {
        $path = "reports/{$reportId}";
        return $this->upload($file, $path, 'public');
    }
}
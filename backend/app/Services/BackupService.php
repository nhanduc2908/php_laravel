<?php

namespace App\Services;

use App\Models\Backup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupService
{
    protected $backupPath = 'backups';
    
    public function create($type = 'database')
    {
        $filename = $this->generateFilename($type);
        $path = "{$this->backupPath}/{$filename}";
        
        if ($type == 'database' || $type == 'both') {
            $this->backupDatabase($path);
        }
        
        if ($type == 'files' || $type == 'both') {
            $this->backupFiles($path);
        }
        
        $backup = Backup::create([
            'filename' => $filename,
            'size' => Storage::size($path),
            'type' => $type,
            'status' => 'completed',
            'path' => $path,
            'created_by' => auth()->id()
        ]);
        
        return $backup;
    }
    
    public function restore($backupId)
    {
        $backup = Backup::findOrFail($backupId);
        
        if ($backup->type == 'database' || $backup->type == 'both') {
            $this->restoreDatabase($backup->path);
        }
        
        if ($backup->type == 'files' || $backup->type == 'both') {
            $this->restoreFiles($backup->path);
        }
        
        $backup->update(['restored_at' => now()]);
        
        return true;
    }
    
    public function list()
    {
        return Backup::orderBy('created_at', 'desc')->get();
    }
    
    public function delete($backupId)
    {
        $backup = Backup::findOrFail($backupId);
        
        if (Storage::exists($backup->path)) {
            Storage::delete($backup->path);
        }
        
        $backup->delete();
        
        return true;
    }
    
    protected function backupDatabase($path)
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        
        $dumpFile = storage_path("app/{$path}.sql");
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($dumpFile)
        );
        
        system($command);
        
        // Compress the dump
        $this->compress($dumpFile);
        
        return true;
    }
    
    protected function backupFiles($path)
    {
        $filesPath = storage_path('app/uploads');
        $zipFile = storage_path("app/{$path}.zip");
        
        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        
        $files = File::allFiles($filesPath);
        foreach ($files as $file) {
            $zip->addFile($file->getRealPath(), $file->getRelativePathname());
        }
        
        $zip->close();
        
        return true;
    }
    
    protected function restoreDatabase($path)
    {
        $sqlFile = storage_path("app/{$path}.sql");
        
        if (File::exists($sqlFile)) {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            
            $command = sprintf(
                'mysql --user=%s --password=%s %s < %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($sqlFile)
            );
            
            system($command);
        }
        
        return true;
    }
    
    protected function restoreFiles($path)
    {
        $zipFile = storage_path("app/{$path}.zip");
        
        if (File::exists($zipFile)) {
            $zip = new \ZipArchive();
            $zip->open($zipFile);
            $zip->extractTo(storage_path('app/uploads'));
            $zip->close();
        }
        
        return true;
    }
    
    protected function generateFilename($type)
    {
        $date = now()->format('Y-m-d_H-i-s');
        return "backup_{$type}_{$date}";
    }
    
    protected function compress($file)
    {
        $compressed = $file . '.gz';
        $bufferSize = 4096;
        
        $source = fopen($file, 'rb');
        $dest = gzopen($compressed, 'wb9');
        
        while (!feof($source)) {
            gzwrite($dest, fread($source, $bufferSize));
        }
        
        fclose($source);
        gzclose($dest);
        
        unlink($file);
        
        return $compressed;
    }
}
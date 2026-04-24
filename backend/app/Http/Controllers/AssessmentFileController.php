<?php

namespace App\Http\Controllers;

use App\Models\AssessmentFile;
use App\Services\AssessmentFileService;
use App\Http\Requests\AssessmentFileRequest;

class AssessmentFileController extends Controller
{
    protected $fileService;

    public function __construct(AssessmentFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $files = AssessmentFile::with('creator', 'server')->paginate(15);
        return $this->success($files, 'Files retrieved');
    }

    public function store(AssessmentFileRequest $request)
    {
        $file = $this->fileService->create($request->validated());
        return $this->success($file, 'File created', 201);
    }

    public function show($id)
    {
        $file = AssessmentFile::with('creator', 'server', 'shares')->findOrFail($id);
        return $this->success($file, 'File retrieved');
    }

    public function update(AssessmentFileRequest $request, $id)
    {
        $file = $this->fileService->update($id, $request->validated());
        return $this->success($file, 'File updated');
    }

    public function destroy($id)
    {
        $this->fileService->delete($id);
        return $this->success(null, 'File deleted');
    }

    public function share($id, $userId)
    {
        $share = $this->fileService->share($id, $userId);
        return $this->success($share, 'File shared');
    }

    public function versions($id)
    {
        $versions = $this->fileService->getVersions($id);
        return $this->success($versions, 'Versions retrieved');
    }

    public function upload(Request $request, $id)
    {
        $request->validate(['file' => 'required|file|max:10240']);
        $file = $this->fileService->uploadAttachment($id, $request->file('file'));
        return $this->success($file, 'File uploaded');
    }

    public function download($id)
    {
        return $this->fileService->download($id);
    }
}
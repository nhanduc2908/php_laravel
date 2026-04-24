<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Services\ImportExportService;
use App\Http\Requests\CriteriaRequest;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    protected $importExport;

    public function __construct(ImportExportService $importExport)
    {
        $this->importExport = $importExport;
    }

    public function index(Request $request)
    {
        $query = Criteria::with('category');
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
        }
        
        $criteria = $query->paginate(20);
        return $this->success($criteria, 'Criteria retrieved');
    }

    public function store(CriteriaRequest $request)
    {
        $criteria = Criteria::create($request->validated());
        return $this->success($criteria, 'Criteria created', 201);
    }

    public function show($id)
    {
        $criteria = Criteria::with('category')->findOrFail($id);
        return $this->success($criteria, 'Criteria retrieved');
    }

    public function update(CriteriaRequest $request, $id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->update($request->validated());
        return $this->success($criteria, 'Criteria updated');
    }

    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();
        return $this->success(null, 'Criteria deleted');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $result = $this->importExport->import($request->file('file'));
        return $this->success($result, 'Import completed');
    }

    public function export()
    {
        return $this->importExport->export();
    }

    public function search(Request $request)
    {
        $results = Criteria::where('name', 'like', "%{$request->q}%")
            ->orWhere('code', 'like', "%{$request->q}%")
            ->limit(10)
            ->get();
        return $this->success($results, 'Search results');
    }
}
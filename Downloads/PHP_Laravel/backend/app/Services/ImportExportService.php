<?php

namespace App\Services;

use App\Models\Criteria;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportExportService
{
    public function import($file)
    {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        $headers = array_shift($rows);
        $imported = 0;
        $errors = [];
        
        foreach ($rows as $rowIndex => $row) {
            $data = array_combine($headers, $row);
            
            try {
                Criteria::updateOrCreate(
                    ['code' => $data['code']],
                    [
                        'category_id' => $data['category_id'],
                        'name' => $data['name'],
                        'description' => $data['description'] ?? null,
                        'weight' => $data['weight'],
                        'status' => $data['status'] ?? 'active'
                    ]
                );
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
            }
        }
        
        return [
            'imported' => $imported,
            'errors' => $errors
        ];
    }
    
    public function export($format = 'xlsx')
    {
        $criteria = Criteria::with('category')->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->setCellValue('A1', 'code');
        $sheet->setCellValue('B1', 'category_id');
        $sheet->setCellValue('C1', 'name');
        $sheet->setCellValue('D1', 'description');
        $sheet->setCellValue('E1', 'weight');
        $sheet->setCellValue('F1', 'status');
        
        $row = 2;
        foreach ($criteria as $item) {
            $sheet->setCellValue('A' . $row, $item->code);
            $sheet->setCellValue('B' . $row, $item->category_id);
            $sheet->setCellValue('C' . $row, $item->name);
            $sheet->setCellValue('D' . $row, $item->description);
            $sheet->setCellValue('E' . $row, $item->weight);
            $sheet->setCellValue('F' . $row, $item->status);
            $row++;
        }
        
        $filename = "criteria_export_" . now()->format('Y-m-d_H-i-s');
        
        if ($format == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $filename .= '.xlsx';
        } else {
            $writer = new Csv($spreadsheet);
            $filename .= '.csv';
        }
        
        $path = storage_path("app/exports/{$filename}");
        $writer->save($path);
        
        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function exportCategories()
    {
        $categories = \App\Models\Category::all();
        return response()->json($categories);
    }
}
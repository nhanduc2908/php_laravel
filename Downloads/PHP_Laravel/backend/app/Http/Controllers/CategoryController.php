<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return $this->success($categories, 'Categories retrieved');
    }

    public function show($id)
    {
        $category = Category::with('criteria')->findOrFail($id);
        return $this->success($category, 'Category retrieved');
    }

    public function criteria($id)
    {
        $category = Category::findOrFail($id);
        $criteria = $category->criteria()->paginate(20);
        return $this->success($criteria, 'Criteria by category');
    }

    public function tree()
    {
        $tree = Category::with('children.criteria')->whereNull('parent_id')->get();
        return $this->success($tree, 'Category tree');
    }

    public function stats($id)
    {
        $category = Category::findOrFail($id);
        $stats = [
            'total_criteria' => $category->criteria()->count(),
            'avg_weight' => $category->criteria()->avg('weight'),
            'compliant_count' => $category->criteria()->where('status', 'compliant')->count()
        ];
        return $this->success($stats, 'Category statistics');
    }
}
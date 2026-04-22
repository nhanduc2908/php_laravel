<?php

namespace App\Repositories;

use App\Models\Criteria;

class CriteriaRepository extends BaseRepository
{
    public function model()
    {
        return Criteria::class;
    }

    public function getByCategory($categoryId, $perPage = 20)
    {
        return $this->model->where('category_id', $categoryId)->paginate($perPage);
    }

    public function search($keyword, $perPage = 20)
    {
        return $this->model->where('name', 'like', "%{$keyword}%")
            ->orWhere('code', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    public function getActiveCriteria()
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByWeightRange($min, $max)
    {
        return $this->model->whereBetween('weight', [$min, $max])->get();
    }

    public function getCriteriaWithCategory($perPage = 20)
    {
        return $this->model->with('category')->paginate($perPage);
    }

    public function getTotalWeight()
    {
        return $this->model->where('status', 'active')->sum('weight');
    }

    public function getCriteriaCountByCategory()
    {
        return $this->model->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->get();
    }

    public function bulkUpdateStatus($ids, $status)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => $status]);
    }
}
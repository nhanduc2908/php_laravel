<?php

namespace App\Wrappers;

class PaginationWrapper
{
    public function paginate($items, $perPage, $currentPage)
    {
        $total = count($items);
        $offset = ($currentPage - 1) * $perPage;
        $paginated = array_slice($items, $offset, $perPage);
        
        return [
            'data' => $paginated,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }
}
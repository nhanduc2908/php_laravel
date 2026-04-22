<?php

namespace App\Wrappers;

class ApiWrapper
{
    public function success($data = null, $message = 'Success', $code = 200)
    {
        return [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ];
    }

    public function error($message = 'Error', $code = 400, $errors = null)
    {
        return [
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => time()
        ];
    }

    public function paginate($data, $total, $perPage, $currentPage)
    {
        return $this->success($data, 'Success', 200, [
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }
}
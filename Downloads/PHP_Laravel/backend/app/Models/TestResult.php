<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $table = 'test_results';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'test_suite', 'test_name', 'status', 'duration', 
        'message', 'trace', 'coverage'
    ];

    protected $casts = [
        'duration' => 'float',
        'coverage' => 'float'
    ];

    public function suite()
    {
        return $this->belongsTo(TestSuite::class, 'test_suite', 'name');
    }

    public function isPassed()
    {
        return $this->status === 'passed';
    }
}
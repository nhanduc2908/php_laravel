<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSuite extends Model
{
    protected $table = 'test_suites';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'tests_count', 'passed', 'failed', 'last_run_at'
    ];

    protected $casts = [
        'last_run_at' => 'datetime'
    ];

    public function results()
    {
        return $this->hasMany(TestResult::class, 'test_suite', 'name');
    }

    public function getPassRate()
    {
        if ($this->tests_count == 0) return 0;
        return round(($this->passed / $this->tests_count) * 100, 2);
    }
}
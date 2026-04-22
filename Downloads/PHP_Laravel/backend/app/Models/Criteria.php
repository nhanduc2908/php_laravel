<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'category_id', 'code', 'name', 'description', 'weight',
        'status', 'answer_type', 'options', 'order', 'reference'
    ];

    protected $casts = [
        'options' => 'array',
        'weight' => 'float'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function calculateScore($answer)
    {
        switch ($this->answer_type) {
            case 'yes_no':
                return $answer === 'yes' ? $this->weight : 0;
            case 'score':
                return ($answer / 100) * $this->weight;
            default:
                return 0;
        }
    }
}
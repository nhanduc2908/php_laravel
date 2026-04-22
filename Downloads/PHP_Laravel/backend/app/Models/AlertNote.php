<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertNote extends Model
{
    protected $table = 'alert_notes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'alert_id', 'note', 'created_by'
    ];

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
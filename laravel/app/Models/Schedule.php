<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['teacher_id', 'branch_id', 'subject_name', 'room', 'day_of_week', 'shift_or_periods'];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}

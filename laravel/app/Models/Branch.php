<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['branch_name', 'address'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modules extends Model
{
    use SoftDeletes, HasFactory;


    public function course()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();;
    }
}

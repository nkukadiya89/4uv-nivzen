<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch  extends Model
{
    use SoftDeletes, HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function users() 
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['video_lesson_id', 'question', 'created_by', 'updated_by', 'deleted_by'];

    public function options()
    {
        return $this->hasMany(QuizOption::class);
    }
}

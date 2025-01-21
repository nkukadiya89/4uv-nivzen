<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name'];

    public function videoLessons()
    {
        return $this->hasMany(VideoLesson::class);
    }

    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, VideoLesson::class, 'training_id', 'video_lesson_id');
    }
}

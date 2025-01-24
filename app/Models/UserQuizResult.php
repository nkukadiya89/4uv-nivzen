<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQuizResult extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'video_lesson_id', 'total_questions', 'correct_answers', 'score_percentage'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videoLesson()
    {
        return $this->belongsTo(VideoLesson::class);
    }
}

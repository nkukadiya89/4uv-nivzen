<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQuizAnswer extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'quiz_id', 'quiz_option_id', 'is_correct'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function quizOption()
    {
        return $this->belongsTo(QuizOption::class);
    }
}

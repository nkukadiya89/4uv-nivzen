<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTrainingActivity extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'training_id', 'video_lesson_id', 'completed_at'];
    protected $table = 'training_activities';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function videoLesson()
    {
        return $this->belongsTo(VideoLesson::class);
    }
}

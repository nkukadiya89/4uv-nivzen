<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Training extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'created_by', 'updated_by', 'deleted_by'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }

    public function videoLessons()
    {
        return $this->hasMany(VideoLesson::class);
    }

    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, VideoLesson::class, 'training_id', 'video_lesson_id');
    }
}

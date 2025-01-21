<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoLesson extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['training_id', 'title', 'description', 'video_url'];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}

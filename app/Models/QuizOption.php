<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizOption extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['quiz_id', 'option', 'is_correct'];
}

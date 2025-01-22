<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ToDo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'todos';
    protected $fillable = [
        'name',
        'datetime',
        'user_id',
        'note',
        'action'
    ];
}

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
        'user_id',
        'name',
        'date',
        'time',
        'is_completed',
        'customer_list',
        'note',
    ];
}

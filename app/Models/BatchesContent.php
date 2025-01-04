<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchesContent extends Model
{ 
    use SoftDeletes, HasFactory;
    protected $table = 'batch_content';
    protected $dates = ['deleted_at'];

}

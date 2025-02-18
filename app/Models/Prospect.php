<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'address',
        'area',
        'city',
        'state',
        'country',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function statuses()
    {
        return $this->hasMany(ProspectStatus::class);
    }

}

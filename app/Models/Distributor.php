<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'enagic_id',
        'mobile_no',
        'email',
        'name',
        'address',
        'area',
        'city',
        'state',
        'country',
        'type',
        'distributor_status',
        'goal_for',
        'upline_name',
        'leader_name',
        'account_status',
    ];
}

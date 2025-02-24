<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProspectStatus extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['prospect_id', 'status', 'date', 'remarks', 'created_by', 'updated_by', 'deleted_by'];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}

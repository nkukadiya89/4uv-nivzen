<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Prospect extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'prospects';

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

            // Soft delete related statuses
            $model->statuses()->update(['deleted_by' => Auth::id()]); // Update deleted_by
            $model->statuses()->delete(); // Soft delete all related statuses
        });
    }
    public function statuses()
    {
        return $this->hasMany(ProspectStatus::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportRequest extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'support_name',
        'description',
        'request_number',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function fromUser() {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser() {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}

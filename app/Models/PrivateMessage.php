<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'sender',
        'receiver',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}

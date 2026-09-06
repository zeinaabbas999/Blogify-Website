<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Media extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
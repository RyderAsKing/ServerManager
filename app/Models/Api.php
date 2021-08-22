<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Api extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'api',
        'api_pass',
        'nick',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

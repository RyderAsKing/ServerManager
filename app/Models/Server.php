<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'server_type',
        'server_id',
        'hostname',
        'ipv4',
        'api_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

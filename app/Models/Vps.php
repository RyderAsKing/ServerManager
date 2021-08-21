<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vps extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'server_type',
        'virtualizor_server_id',
        'hetzner_server_id',
        'hostname',
        'ipv4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

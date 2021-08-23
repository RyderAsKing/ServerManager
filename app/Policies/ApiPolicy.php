<?php

namespace App\Policies;

use App\Models\Api;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiPolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function use_api(User $user, Api $api)
    {
        return $user->id == $api->user_id;
    }
}

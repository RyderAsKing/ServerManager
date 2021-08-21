<?php

namespace App\Policies;

use App\Models\Vps;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VpsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function use(User $user, Vps $vps)
    {
        return $user->id == $vps->user_id;
    }
}

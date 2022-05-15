<?php

namespace App\Policies;

use App\User;
use App\design;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesignPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\design  $design
     * @return mixed
     */
    public function view(User $user, design $design)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\design  $design
     * @return mixed
     */
    public function update(User $user, design $design)
    {
        return $design->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\design  $design
     * @return mixed
     */
    public function delete(User $user, design $design)
    {
        return $design->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\design  $design
     * @return mixed
     */
    public function restore(User $user, design $design)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\design  $design
     * @return mixed
     */
    public function forceDelete(User $user, design $design)
    {
        //
    }
}

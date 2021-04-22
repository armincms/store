<?php

namespace Armincms\Store\Policies;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization; 

    /**
     * Determine whether the user can view the = user.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $Model
     * @return mixed
     */
    public function view(Authenticatable $user, Model $Model)
    {
        //
    }

    /**
     * Determine whether the user can create = users.
     *
     * @param  \Core\User\Models\User  $user
     * @return mixed
     */
    public function create(Authenticatable $user)
    {
        //
    }

    /**
     * Determine whether the user can update the = user.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $Model
     * @return mixed
     */
    public function update(Authenticatable $user, Model $Model)
    {
        //
    }

    /**
     * Determine whether the user can delete the = user.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $Model
     * @return mixed
     */
    public function delete(Authenticatable $user, Model $Model)
    {
        //
    }

    /**
     * Determine whether the user can restore the = user.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $Model
     * @return mixed
     */
    public function restore(Authenticatable $user, Model $Model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the = user.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $Model
     * @return mixed
     */
    public function forceDelete(Authenticatable $user, Model $Model)
    {
        //
    }
}

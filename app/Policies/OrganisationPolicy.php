<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    public function edit(User $user, Organisation $organisation)
    {
        return $user
            ->organisations()
            ->where("organisations.id", $organisation->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Organisation $organisation)
    {
        return $user
            ->organisations()
            ->where("organisations.id", $organisation->id)
            ->exists();
    }

    public function createService(User $user, Organisation $organisation)
    {
        return $user
            ->organisations()
            ->where("organisations.id", $organisation->id)
            ->exists();
    }

    public function storeService(User $user, Organisation $organisation)
    {
        return $user
            ->organisations()
            ->where("organisations.id", $organisation->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Organisation $organisation)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Organisation $organisation)
    {
        //
    }
}

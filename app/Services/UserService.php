<?php

namespace App\Services;

use App\Models\User;

class UserService extends Service
{
    /**
     * Create a user.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update the user.
     *
     * @param  \App\Models\User $user
     * @param  array $data
     * @return void
     */
    public function update(User $user, array $data): void
    {
        $user->update($data);
    }

    /**
     * Delete the user.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}

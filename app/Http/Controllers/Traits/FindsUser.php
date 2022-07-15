<?php

namespace App\Http\Controllers\Traits;

use App\Models\User;
use App\Exceptions\ApiException;

trait FindsUser
{
    /**
     * Get user by id.
     *
     * @param  string|int $id
     * @return \App\Models\User
     */
    protected function findUserById(string|int $id): User
    {
        return $this->findUser(value: $id);
    }

    /**
     * Get user by email.
     *
     * @param  string $email
     * @return \App\Models\User
     */
    protected function findUserByEmail(string $email): User
    {
        return $this->findUser(field: 'email', value: $email);
    }

    /**
     * Get user by field and value.
     *
     * @param  string $field
     * @param  string $value
     * @return \App\Models\User
     * @throws \App\Exceptions\ApiException
     */
    private function findUser(string $field = 'id', string $value): User
    {
        $user = User::where($field, $value)->first();

        if (!$user) {
            throw new ApiException(ApiException::NOT_FOUND, 'resources.user');
        }

        return $user;
    }
}

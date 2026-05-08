<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users.
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Find user by id.
     */
    public function findById(
        int|string $id
    ): ?User {
        return User::find($id);
    }

    /**
     * Create new user.
     *
     * @param array<string, mixed> $data
     */
    public function create(
        array $data
    ): User {
        return User::create($data);
    }

    /**
     * Update user.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        int|string $id,
        array $data
    ): bool {
        $user = $this->findById($id);

        return $user
            ? $user->update($data)
            : false;
    }

    /**
     * Delete user.
     */
    public function delete(
        int|string $id
    ): bool {
        $user = $this->findById($id);

        return $user
            ? $user->delete()
            : false;
    }

    /**
     * Find user by email.
     */
    public function findByEmail(
        string $email
    ): ?User {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * Find user by OAuth provider.
     */
    public function findByProvider(
        string $provider,
        string $providerId
    ): ?User {
        return User::query()
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }
}

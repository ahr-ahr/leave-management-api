<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
    extends BaseRepositoryInterface
{
    /**
     * Find user by email.
     */
    public function findByEmail(
        string $email
    ): ?User;

    /**
     * Find user by OAuth provider.
     */
    public function findByProvider(
        string $provider,
        string $providerId
    ): ?User;
}

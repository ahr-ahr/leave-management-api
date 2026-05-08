<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * Get all records.
     */
    public function all();

    /**
     * Find record by id.
     */
    public function findById(
        int|string $id
    );

    /**
     * Create new record.
     *
     * @param array<string, mixed> $data
     */
    public function create(
        array $data
    );

    /**
     * Update record.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        int|string $id,
        array $data
    );

    /**
     * Delete record.
     */
    public function delete(
        int|string $id
    ): bool;
}

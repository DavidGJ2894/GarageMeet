<?php

namespace App\Contracts\Repositories;

use App\Models\User;

/**
 * User Repository Interface
 *
 * Defines the contract for user data access operations.
 * All database interactions for users should go through this interface.
 *
 * @package App\Contracts\Repositories
 */
interface UserRepositoryInterface
{
    /**
     * Create a new user
     *
     * @param array $data User data (password will be hashed automatically)
     * @return User Created user model instance
     */
    public function create(array $data): User;

    /**
     * Find user by ID with relationships
     *
     * @param int $id User ID
     * @return User|null User model with typeUser and mechanicalWorkshop relationships, or null if not found
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email address
     *
     * @param string $email User email
     * @return User|null User model or null if not found
     */
    public function findByEmail(string $email): ?User;

    /**
     * Update user data
     *
     * @param int $id User ID
     * @param array $data Update data (password will be hashed if provided)
     * @return bool True if update was successful
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete user
     *
     * @param int $id User ID
     * @return bool True if deletion was successful
     */
    public function delete(int $id): bool;
}

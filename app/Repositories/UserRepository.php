<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * User Repository
 *
 * Handles all database operations for User model.
 * Implements data access layer for user-related queries.
 *
 * @package App\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user
     *
     * Automatically hashes the password before storing in database.
     *
     * @param array $data User data including plain text password
     * @return User Created user model instance
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    /**
     * Find user by ID with relationships
     *
     * Eager loads typeUser and mechanicalWorkshop relationships.
     *
     * @param int $id User ID
     * @return User|null User model with relationships or null if not found
     */
    public function findById(int $id): ?User
    {
        return User::with(['typeUser', 'mechanicalWorkshop'])->find($id);
    }

    /**
     * Find user by email address
     *
     * @param string $email User email
     * @return User|null User model or null if not found
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Update user data
     *
     * Automatically hashes password if it's being updated.
     *
     * @param int $id User ID
     * @param array $data Update data
     * @return bool True if update was successful
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When user not found
     */
    public function update(int $id, array $data): bool
    {
        $user = User::findOrFail($id);

        // Hash password if being updated
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $user->update($data);
    }

    /**
     * Delete user from database
     *
     * @param int $id User ID
     * @return bool True if deletion was successful
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When user not found
     */
    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}

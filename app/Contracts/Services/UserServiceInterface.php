<?php

namespace App\Contracts\Services;

use App\Models\User;

/**
 * User Service Interface
 *
 * Defines the contract for user-related business logic operations
 * including authentication, registration, and profile management.
 *
 * @package App\Contracts\Services
 */
interface UserServiceInterface
{
    /**
     * Register a new user and authenticate them
     *
     * @param array $data User registration data
     * @return array Array containing token and user data
     * @throws \Exception When registration fails
     */
    public function register(array $data): array;

    /**
     * Get the authenticated user with relationships
     *
     * @return array Formatted user data with mechanical workshop info
     * @throws \Exception When user is not authenticated
     */
    public function getAuthenticatedUser(): array;

    /**
     * Update user profile information
     *
     * @param int $userId User ID to update
     * @param array $data Update data
     * @param string|null $currentPassword Current password for verification (required when changing password)
     * @return array Updated user data
     * @throws \Exception When user not found or current password is incorrect
     */
    public function updateUser(int $userId, array $data, ?string $currentPassword = null): array;

    /**
     * Format user data for API response
     *
     * @param User|null $user User model instance
     * @return array Formatted user data array
     * @throws \Exception When user is null
     */
    public function formatUserResponse($user): array;

    /**
     * Authenticate user and get login data
     *
     * @param array $credentials User credentials (email and password)
     * @return array Array containing token and user data
     * @throws \Exception When credentials are invalid
     */
    public function getLoginData(array $credentials): array;
}

<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * User Service
 *
 * Handles business logic for user operations including authentication,
 * registration, profile updates, and data formatting.
 *
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository instance
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * UserService constructor
     *
     * @param UserRepositoryInterface $userRepository Repository for user data access
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     *
     * Creates a new user account, sets up Stripe customer, authenticates the user,
     * and returns authentication data.
     *
     * @param array $data User registration data (name, last_name, email, password, type_user)
     * @return array Array containing 'token' and 'user' data
     * @throws \Exception When user creation or Stripe setup fails
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the user
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'type_users_id' => $data['type_user'],
            ]);

            // 2. Setup Stripe customer
            $user->createAsStripeCustomer();

            // 3. Authenticate user and generate token
            $token = auth('api')->login($user);

            // 4. Reload user with relationships
            $user = $this->userRepository->findById($user->users_id);

            // 5. Return authentication data
            return [
                'token' => $token,
                'user' => $this->formatUserResponse($user)
            ];
        });
    }

    /**
     * Get authenticated user with relationships
     *
     * Retrieves the currently authenticated user's data including their
     * mechanical workshop information if available.
     *
     * @return array Formatted user data with relationships
     * @throws \Exception When user is not authenticated
     */
    public function getAuthenticatedUser(): array
    {
        $user = auth('api')->user();
        if (!$user) {
            throw new \Exception('Usuario no autenticado');
        }

        $user = $this->userRepository->findById($user->users_id);
        return $this->formatUserResponse($user);
    }

    /**
     * Update user profile data
     *
     * Updates the user's information and syncs changes with Stripe if applicable.
     * Requires current password verification when changing password.
     *
     * @param int $userId User ID to update
     * @param array $data Update data (name, last_name, email, password, type_users_id)
     * @param string|null $currentPassword Current password for verification when changing password
     * @return array Updated user data
     * @throws \Exception When user not found or current password is incorrect
     */
    public function updateUser(int $userId, array $data, ?string $currentPassword = null): array
    {
        return DB::transaction(function () use ($userId, $data, $currentPassword) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                throw new \Exception('Usuario no encontrado');
            }

            // Verify current password when changing password
            if (isset($data['password']) && $currentPassword) {
                if (!Hash::check($currentPassword, $user->password)) {
                    throw new \Exception('La contraseña actual es incorrecta');
                }
            }

            // Prepare data for update (excluding current_password and password_confirmation)
            $updateData = [];
            foreach (['name', 'last_name', 'email', 'password', 'type_users_id'] as $field) {
                if (isset($data[$field])) {
                    $updateData[$field] = $data[$field];
                }
            }

            $this->userRepository->update($userId, $updateData);

            // Sync with Stripe if user has stripe_id
            if ($user->hasStripeId()) {
                $user->syncStripeCustomerDetails();
            }

            $user = $this->userRepository->findById($userId);

            return $this->formatUserResponse($user);
        });
    }

    /**
     * Format user data for API response
     *
     * Transforms the user model into a formatted array including
     * mechanical workshop data if available.
     *
     * @param mixed $user User model instance
     * @return array Formatted user data array
     * @throws \Exception When user is null or not found
     */
    public function formatUserResponse($user): array
    {
        if (!$user) {
            throw new \Exception('Usuario no encontrado');
        }

        $userData = [
            'id' => $user->users_id,
            'email' => $user->email,
            'type_user' => $user->typeUser->name ?? null,
            'name' => $user->name,
            'last_name' => $user->last_name,
        ];

        if ($user->mechanicalWorkshop) {
            $mechanicalData = $user->mechanicalWorkshop->toArray();
            $userData['mechanical_workshop'] = $mechanicalData;
        } else {
            $userData['mechanical_workshop'] = null;
        }

        return $userData;
    }

    /**
     * Authenticate user and get login data
     *
     * Validates credentials, generates JWT token, and returns formatted user data.
     *
     * @param array $credentials User credentials (email and password)
     * @return array Array containing 'token' and 'user' data
     * @throws \Exception When credentials are invalid
     */
    public function getLoginData(array $credentials): array
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception('Credenciales incorrectas');
        }

        return [
            'token' => $token,
            'user' => $this->getAuthenticatedUser()
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserServiceInterface;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Authentication Controller
 *
 * Handles user authentication operations including login, registration,
 * profile updates, and JWT token management.
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * User service instance
     *
     * @var UserServiceInterface
     */
    private UserServiceInterface $userService;

    /**
     * AuthController constructor
     *
     * @param UserServiceInterface $userService User service for business logic
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Authenticate user and generate JWT token
     *
     * Validates user credentials and returns a JWT token along with user data
     * if authentication is successful.
     *
     * @param LoginUserRequest $request Validated login request containing email and password
     * @return \Illuminate\Http\JsonResponse JSON response with access token and user data
     * @throws \Exception When authentication fails or service error occurs
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $loginData = $this->userService->getLoginData($request->validated());
            return response()->json([
                'access_token' => $loginData['token'],
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => $loginData['user']
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('Error en el login', $e->getMessage(), 500);
        }
    }

    /**
     * Register a new user
     *
     * Creates a new user account with the provided information, sets up Stripe customer,
     * authenticates the user automatically, and returns a JWT token.
     *
     * @param RegisterUserRequest $request Validated registration request with user details
     * @return \Illuminate\Http\JsonResponse JSON response with access token and user data (201 Created)
     * @throws \Exception When registration fails or service error occurs
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $userData = $this->userService->register($request->validated());

            return response()->json([
                'access_token' => $userData['token'],
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => $userData['user']
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al registrar usuario', $e->getMessage(), 500);
        }
    }

    /**
     * Update authenticated user's profile
     *
     * Updates the current authenticated user's information. Requires current password
     * verification when changing the password. Syncs changes with Stripe if applicable.
     *
     * @param UpdateUserRequest $request Validated update request with user fields to update
     * @return \Illuminate\Http\JsonResponse JSON response with success message and updated user data
     * @throws \Exception When update fails, user not found, or current password is incorrect
     */
    public function updateUser(UpdateUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->getAuthenticatedUser();
            $currentPassword = $data['current_password'] ?? null;

            $userData = $this->userService->updateUser(
                $user['users_id'],
                $data,
                $currentPassword
            );

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'user' => $userData
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al actualizar usuario', $e->getMessage(), 400);
        }
    }

    /**
     * Get authenticated user's profile
     *
     * Retrieves the currently authenticated user's information including
     * their mechanical workshop data if available.
     *
     * @return \Illuminate\Http\JsonResponse|array JSON response with user profile data
     * @throws \Exception When user is not authenticated
     */
    public function me()
    {
        try {
            return $this->userService->getAuthenticatedUser();
        } catch (\Exception $e) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
    }

    /**
     * Logout user and invalidate JWT token
     *
     * Logs out the currently authenticated user by invalidating their JWT token
     * and adding it to the blacklist.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with success message
     */
    public function logout()
    {
        auth('api')->logout(true);
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    /**
     * Refresh JWT token
     *
     * Generates a new JWT token for the authenticated user, extending their session.
     * The old token is invalidated.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with new access token
     */
    public function refresh()
    {
        $token = JWTAuth::refresh();
        return $this->respondWithToken($token);
    }

    /**
     * Format token response structure
     *
     * Helper method to format the standard JWT token response structure
     * with token type and expiration time.
     *
     * @param string $token JWT access token
     * @return \Illuminate\Http\JsonResponse JSON response with formatted token data
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}

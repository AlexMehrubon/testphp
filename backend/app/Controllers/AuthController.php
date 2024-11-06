<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(array $request): string
    {
        try {
            if (empty($request['jsonData']['name']) || empty($request['jsonData']['password'])) {
                return $this->errorResponse('Username and password are required', 400);
            }

            $name = $request['jsonData']['name'];
            $password = $request['jsonData']['password'];

            $user = $this->userRepository->getByName($name);

            if (!$user) {
                return $this->errorResponse('User does not exist', 404);
            }

            if ($password !== $user['password']) {
                return $this->errorResponse('Incorrect password', 401);
            }

            $this->setUserSession(new User($user['name'], $user['password'], $user['id']));
            return json_encode(['status' => 'success', 'message' => $user]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function register(array $request): string
    {
        try {
            if (empty($request['jsonData']['name']) || empty($request['jsonData']['password']) || empty($request['jsonData']['password_confirmation'])) {
                return $this->errorResponse('Too few arguments', 400);
            }

            $name = $request['jsonData']['name'];
            $password = $request['jsonData']['password'];
            $password_confirmation = $request['jsonData']['password_confirmation'];

            if ($password !== $password_confirmation)
                return $this->errorResponse('Passwords do not match', 400);


            if ($this->userRepository->getByName($name))
                return $this->errorResponse('Username already taken', 409);


            $user = new User($name, $password);
            if (!$this->userRepository->create($user))
                return $this->errorResponse('Server error', 500);
            $userDB = $this->userRepository->getByName($name);
            $user->id = $userDB['id'];

            $this->setUserSession($user);
            return json_encode(['status' => 'success', 'user' => $_SESSION['user']]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function check(): string
    {
        return json_encode([
            'authenticated' => isset($_SESSION['user']),
            'user' => $_SESSION['user'] ?? null,
        ]);
    }

    public function logout(): string
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie("PHPSESSID", '', time() - 3600, '/');
        }

        session_destroy();
        return json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
    }

    private function setUserSession(User $user): void
    {
        $_SESSION['user'] = [
            'id' => $user->id,
            'name' => $user->name,
        ];
        setcookie("PHPSESSID", session_id(), [
            'expires' => time() + 86400,
            'path' => '/',
            'domain' => 'localhost',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'None',
        ]);
    }

    private function errorResponse(string $message, int $statusCode): string
    {
        http_response_code($statusCode);
        return json_encode(['status' => 'failed', 'message' => $message]);
    }
}

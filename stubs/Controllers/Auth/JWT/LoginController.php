<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    protected bool $authAccess = false;

    public function loginAction()
    {
        $credentials = [
            'email' => $this->request->getJsonRawBody()->email,
            'password' => $this->request->getJsonRawBody()->password
        ];

        if (! $token = $this->auth->attempt($credentials, true)) {
            return json_encode(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function meAction()
    {
        return json_encode($this->auth->user());
    }

    public function logoutAction()
    {
        $this->auth->logout();

        return json_encode(['message' => 'Successfully logged out']);
    }

    public function refreshAction()
    {
        return $this->respondWithToken($this->auth->refresh());
    }

    protected function respondWithToken($token)
    {
        return json_encode([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ]);
    }

    public function authAccess()
    {
        return $this->authAccess;
    }
}

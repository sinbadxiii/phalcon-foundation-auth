<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    protected bool $authAccess = false;

    public function initialize()
    {
        $this->view->disable();
    }

    public function loginAction()
    {
        $credentials = [
            'email' => $this->request->getJsonRawBody()->email,
            'password' => $this->request->getJsonRawBody()->password
        ];

        $this->auth->claims(['aud' => [
            $this->request->getURI()
        ]]);

        if (! $token = $this->auth->attempt($credentials)) {
            $this->response->setJsonContent(['error' => 'Unauthorized'])->send();
        }

        return $this->respondWithToken($token);
    }

    public function meAction()
    {
        $this->response->setJsonContent($this->auth->user())->send();
    }

    public function logoutAction()
    {
        $this->auth->logout();

        $this->response->setJsonContent(['message' => 'Successfully logged out'])->send();
    }

    public function refreshAction()
    {
        return $this->respondWithToken($this->auth->refresh());
    }

    protected function respondWithToken($token)
    {
        $this->response->setJsonContent($token->toResponse())->send();
    }

    public function authAccess()
    {
        return $this->authAccess;
    }
}

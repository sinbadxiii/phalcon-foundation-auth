<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Models\Users;
use Phalcon\Mvc\Controller;

class RegisterController extends Controller
{
    public function registerFormAction()
    {
        $this->view->pick('auth/register/registerForm');
    }

    public function registerAction()
    {
        $user = new Users();
        $user->assign([
            'name' => $this->request->getPost("name"),
            'email' => $this->request->getPost("email"),
            'password' => $this->request->getPost("password"),
        ]);
        $user->save();

        $this->auth->login($user);

        return  $this->response->redirect("/");
    }
}


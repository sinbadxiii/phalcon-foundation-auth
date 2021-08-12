<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Phalcon\Mvc\Controller;
use Sinbadxiii\PhalconFoundationAuth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected bool $authAccess = false;
    protected const MAX_AUTH_ATTEMPTS_COUNT = 3;
}


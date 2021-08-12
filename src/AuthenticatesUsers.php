<?php

namespace Sinbadxiii\PhalconFoundationAuth;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    public function loginFormAction()
    {
        $this->view->pick("auth/login/loginForm");
    }

    public function loginAction()
    {
//        $this->validateLogin($request);

        if ($this->limiter()->tooManyAttempts(
            $this->throttleKey(), $this->maxAttempts()
        )) {
            return $this->blockingLogin();
        }

        if ($this->attemptLogin()) {
            return $this->succesLogin();
        }

        $this->incrementLoginAttempts();

        return $this->failLogin();
    }

    protected function succesLogin()
    {
        $this->clearLoginAttempts();
        return $this->response->redirect("/admin");
    }

    private function attemptLogin()
    {
        $remember = $this->request->getPost('remember') ? true : false;
        return $this->auth->attempt($this->credentials(), $remember);
    }

    private function blockingLogin()
    {
        $this->flashSession->error('Вы заблокированы');
        return $this->response->redirect(
            "/login", true
        );
    }

    private function credentials()
    {
        $username = $this->request->getPost($this->usernameKey(), 'string');
        $password = $this->request->getPost('password', 'string');

        return [$this->usernameKey() => $username, 'password' => $password];
    }

    protected function sendLoginResponse()
    {
//        $request->session()->regenerate();
//
//        $this->clearLoginAttempts($request);
//
//        return $this->authenticated($request, $this->guard()->user())
//                ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated($user)
    {
        //
    }

    protected function sendFailedLoginResponse()
    {
//        throw ValidationException::withMessages([
//            $this->username() => [trans('auth.failed')],
//        ]);
    }

    public function usernameKey()
    {
        return 'email';
    }

    public function logoutAction()
    {
        $this->auth->logout();

        return $this->response->redirect("/");
    }

    protected function validateLogin()
    {
//        $request->validate([
//            $this->username() => 'required|string',
//            'password' => 'required|string',
//        ]);
    }

    private function failLogin()
    {
        $attemptsLeft = $this->limiter()->retriesLeft(
            $this->throttleKey(), $this->maxAttempts()
        );

//        $this->flashSession->error(
//            "Неверное имя пользователя или пароль. Oсталось {$attemptsLeft} попыток"
//        );

        return $this->response->redirect("/login");
    }

    protected function loggedOut()
    {
        //
    }

    protected function guard()
    {
//        return Auth::guard();
    }
}

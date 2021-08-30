<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconFoundationAuth\Lib\Internal\Operations;

class CopyOperation implements OperationInterface
{
    protected $baseDirApp;
    protected $controllersDir;
    protected $baseDirLib;

    protected $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function make()
    {
       $this->baseDirApp = dirname(__DIR__, 7) . "/" . ($this->attributes['base-dir-app'] ?? 'app');
       $this->controllersDir = $this->baseDirApp . "/" . ($this->attributes['controllers-dir'] ?? 'controllers');
       $this->baseDirLib = dirname(__DIR__, 4) . "/stubs/";

       $this->copyControllers();
       $this->copyMiddlewares();
       $this->copyViews();
    }

    private function copyControllers()
    {
        if (!dir($this->controllersDir . "/Auth") &&!mkdir($concurrentDirectory = $this->controllersDir . "/Auth")
            && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        copy($this->baseDirLib . '/Controllers/Auth/LoginController.php', $this->controllersDir . "/Auth/LoginController.php");
        copy($this->baseDirLib . '/Controllers/Auth/RegisterController.php', $this->controllersDir . "/Auth/RegisterController.php");
    }

    private function copyMiddlewares()
    {
        if (!dir($this->baseDirApp . "/Security") && !mkdir($concurrentDirectory = $this->baseDirApp . "/Security") && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        copy($this->baseDirLib . '/Security/Authenticate.php', $this->baseDirApp . "/Security/Authenticate.php");
    }

    private function copyViews()
    {
        $viewsDir = [
            'views/auth', 'views/auth/login', 'views/auth/register'
        ];

        foreach ($viewsDir as $viewDir) {
            if (!dir($this->baseDirApp . "/" . $viewDir ) && !mkdir($concurrentDirectory = $this->baseDirApp . "/" . $viewDir) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        copy($this->baseDirLib . '/views/auth/login/loginForm.volt', $this->baseDirApp . "/views/auth/login/loginForm.volt");
        copy($this->baseDirLib . '/views/auth/register/registerForm.volt', $this->baseDirApp . "/views/auth/register/registerForm.volt");
    }
}
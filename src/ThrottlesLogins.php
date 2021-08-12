<?php

namespace Sinbadxiii\PhalconFoundationAuth;
use Phalcon\Helper\Str;
use Sinbadxiii\PhalconThrottle\RateLimiter;

trait ThrottlesLogins
{
    protected function incrementLoginAttempts()
    {
        $this->limiter()->hit(
            $this->throttleKey(), $this->decayMinutes() * 60
        );
    }

    protected function clearLoginAttempts()
    {
        $this->limiter()->clear($this->throttleKey());
    }

    protected function throttleKey(): string
    {
        $rule = 'Any-Latin; Latin-ASCII;';
        $transliterator = \Transliterator::create($rule);

        return $transliterator->transliterate(
            Str::lower($this->request->getPost()[$this->usernameKey()]) . ':' .
            $this->request->getClientAddress()
        );
    }

    protected function limiter()
    {
        return new RateLimiter($this->cache->getAdapter());
    }

    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }
}

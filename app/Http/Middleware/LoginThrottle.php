<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginThrottle
{
    public function __construct(protected RateLimiter $limiter)
    {
    }

    /**
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $this->throttleKey($request);

        if ($this->limiter->tooManyAttempts($key, 5)) {
            event(new Lockout($request));
            $seconds = $this->limiter->availableIn($key);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $this->limiter->hit($key);

        return $next($request);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->string('email')) . '|' . $request->ip());
    }
}

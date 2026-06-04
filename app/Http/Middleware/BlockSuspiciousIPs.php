<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BlockSuspiciousIPs
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;
    protected $blockMinutes = 10;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = $this->throttleKey($ip);

        if (Cache::has($key . 'blocked')) {
            Session::flash('error', 'Your IP has been temporarily blocked for ' . $this->blockMinutes . ' minute(s) due to suspicious activity.');
            return redirect()->back();
        }

        if (Cache::has($key)) {
            $attempts = Cache::get($key);
            if ($attempts >= $this->maxAttempts) {
                Cache::put($key . 'blocked', true, $this->blockMinutes * 60);
                Log::warning("IP $ip has been blocked for $this->blockMinutes minute(s) due to suspicious activity.");
                Session::flash('error', 'Your IP has been temporarily blocked for ' . $this->blockMinutes . ' minute(s) due to suspicious activity.');
                return redirect()->back();
            } else {
                Cache::put($key, $attempts + 1, $this->decayMinutes * 60);
            }
        } else {
            Cache::put($key, 1, $this->decayMinutes * 60);
        }

        return $next($request);
    }

    protected function throttleKey($ip)
    {
        return 'throttle:' . sha1($ip);
    }
}

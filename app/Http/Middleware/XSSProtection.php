<?php

namespace App\Http\Middleware;

use Closure;

class XSSProtection
{
    public function handle($request, Closure $next)
    {
        $log = "\n Request: " . date('Y-m-d H:i:s') . ': ' . $request->ip();
        \Storage::disk('local')->append('log_requests.json', $log);

        if (!in_array(strtolower($request->method()), ['put', 'post', 'patch'])) {
            return $next($request);
        }

        $input = $request->all();
        array_walk_recursive($input, function(&$value, $key) {
            if (strtolower($key) != 'description' && strtolower($key) != 'reason' && strtolower($key) != 'message') {
                $value = htmlspecialchars($value);
            }
        });
        $request->merge($input);

        return $next($request);
    }
}

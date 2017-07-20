<?php

namespace App\Http\Middleware;

use Closure;

class XSSProtection
{
    public function handle($request, Closure $next)
    {
        if (!in_array(strtolower($request->method()), ['put', 'post', 'patch'])) {
            return $next($request);
        }

        $input = $request->all();
        array_walk_recursive($input, function($item, $key) {
            if (!strtolower($key) == 'description') {
                $input[$key] = htmlspecialchars($item);
            }
        });
        $request->merge($input);

        return $next($request);
    }
}

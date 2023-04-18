<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    use ApiResponser;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @param  Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_null(auth()->user())) {
            return $this->apiResponse(
                'Unauthenticated',
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}

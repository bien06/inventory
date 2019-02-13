<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DeniedIfNotAdmin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin_user') {
        if (!Auth::guard('admin_user')->check()) {
            return redirect('admin_login');
        }
        
        return $next($request);
    }

}

<?php


namespace App\Http\Middleware;

use App\User;
use Closure;
class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!User::is_teacher() && !User::is_admin()){
            return redirect('/');
        }
        return $next($request);
    }
}

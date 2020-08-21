<?php

namespace App\Http\Middleware;

use Closure;
use \Auth;

class RoleMiddleware
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
        $not_allowed_user_array = array('accountdirectory', 'wonprojectsummary', 'drafterhours', 'editaccount');
        $uri = $request->path();
        $user = Auth::user();

        if($user['role'] == 'sudo' || $user['role'] == 'admin'){
            return $next($request);
        }
        elseif($user['role'] == 'proposer'){
            if($uri != "accountdirectory"){
                return $next($request);
            }
            else{
                return redirect('home');
            }
        }
        elseif($user['role'] == 'user'){
            if(!in_array($uri, $not_allowed_user_array)){
                return $next($request);
            }
            else{
                return redirect('home');
            }
        }
        else{
            return redirect('login');
        }
    }
}

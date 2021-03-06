<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ProjectHasMember;

class CheckProjectAdmin
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
        $exists = (new ProjectHasMember)->checkProjectAdmin($request->id);

        //not an admin
        if(!$exists){
            return response()->view('errors.401');
        }
        else{
            return $next($request);    
        }
    }
}

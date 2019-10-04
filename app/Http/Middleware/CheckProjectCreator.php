<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ProjectHasMember;

class CheckProjectCreator
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
        $exists = (new ProjectHasMember)->checkProjectCreator($request->id);

        //not an admin
        if(!$exists){
            return response()->view('errors.401');
        }
        else{
            return $next($request);    
        }
    }
}

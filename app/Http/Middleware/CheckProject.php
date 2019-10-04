<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ProjectHasMember;

class CheckProject
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
        //dd($request);
        $exists = (new ProjectHasMember)->checkProjectMember($request->id);
        if(!$exists){
            return response()->view('errors.404');
        }
        else{
            return $next($request);    
        }
        
    }
}

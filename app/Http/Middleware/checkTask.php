<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class checkTask
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
    
        $existsTask = DB::table('tasks')
        ->where([['id','=',$request->id],['project_id','=',$request->project_id]])
        ->first();
       
        if(!$existsTask){
            return response()->view('errors.404');
        }
        else{
            return $next($request);    
        }

    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Task;

class CheckTask
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
    
        $existsTask = (new Task)->checkTask($request->id,$request->project_id);
       
        if(!$existsTask){
            return response()->view('errors.404');
        }
        else{
            return $next($request);    
        }

    }
}

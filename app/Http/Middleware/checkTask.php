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
        
        $existsMember = DB::table('task_has_member')
        ->where([['member_id','=',auth()->id()],['task_id','=',$request->id]])
        ->first();

        $existsTask = DB::table('task')
        ->where([['id','=',$request->id],['project_id','=',$request->project_id]])
        ->first();
        //dd($existsTask == null);
        if(!$existsMember || !$existsTask){
            return response()->view('errors.404');
        }
        else{
            return $next($request);    
        }

    }
}

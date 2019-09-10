<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class checkProjectAdmin
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
        $exists = DB::table('project_has_member')
                    ->where([['member_id','=',auth()->id()],['project_id','=',$request->id],['role','=',true]])
                    ->first();

        //not an admin
        if(!$exists){
            return response()->view('errors.401');
        }
        else{
            return $next($request);    
        }
    }
}

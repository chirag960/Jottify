<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Closure;

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
        $exists = DB::table('project_has_member')
                    ->where([['member_id','=',auth()->id()],['project_id','=',$request->id]])
                    ->first();
        if(!$exists){
            return redirect('notFound');
        }
        else{
            return $next($request);    
        }
        
    }
}

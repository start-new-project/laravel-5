<?php

namespace App\Http\Middleware;

use Closure; 

class ActiveUserMiddleware
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
        if(!empty(auth()->id())&&(auth()->user()->isBlocked())){
            $this->logout($request);
        }
        return $next($request);
    }


    public function logout($request){
  
        $request->session()->invalidate(); 

    }


}

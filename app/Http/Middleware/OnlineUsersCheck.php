<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OnlineUsersCheck
{
    /*
     * this middleware creates cache to check if user is online or offline
     * first we need to check if user is Authenticated or not.
     * then if true, we add his data in the cache.
     * cache expired every one minute and recreated with the new online users.
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            /*
             * set the expiration time after a minute from creation
             */
            $exp = Carbon::now()->addMinute(1);
            /*
             * create the cache.
             */
            Cache::put('online_user-'.Auth::user()->id, true, $exp);
        }
        return $next($request);
    }
}

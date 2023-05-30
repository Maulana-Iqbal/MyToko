<?php

namespace App\Http\Middleware;

use App;
use Closure;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$ips)
    {
        $access = array_filter(array_map(function ($v) {
            return ($star = strpos($v, "*")) ? (substr($this->getLocalIP(), 0, $star) == substr($v, 0, $star))
                : ($this->getLocalIP() == $v);
        }, $ips));

        return $access ? $next($request) : App::abort(403);
    }

    function getLocalIP()
    {
        exec("ipconfig /all", $output);
        foreach ($output as $line) {
            if (preg_match("/(.*)IPv4 Address(.*)/", $line)) {
                $ip = $line;
                $ip = str_replace("IPv4 Address. . . . . . . . . . . :", "", $ip);
                $ip = str_replace("(Preferred)", "", $ip);
            }
        }
        return trim($ip);
    }
}

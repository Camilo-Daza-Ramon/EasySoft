<?php

namespace App\Http\Middleware;

use Closure;

class RestringirPorIP
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
        // Lista de IPs permitidas
        $ipsPermitidas = [
            '127.0.0.1',      // localhost IPv4
            '::1',            // localhost IPv6
            '172.17.0.50',    // Tu IP Wi-Fi
            '192.168.1.10',
            '192.168.1.2',
            // Agrega aquí las IPs que quieras permitir
        ];

        if (!in_array($request->ip(), $ipsPermitidas)) {
            abort(403, 'Acceso restringido');
        }

        return $next($request);
    }
}

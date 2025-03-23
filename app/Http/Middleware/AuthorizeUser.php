<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
       $user_role = $request->user()->getRole();//ambil data level kode dari login user
       if (in_array($user_role, $roles)) {
           return $next($request);
       }
       abort(403, 'Kamu tidak punya akses ke halaman ini');//jika user tidak memiliki role tertentu maka kembalikan error
    }
}

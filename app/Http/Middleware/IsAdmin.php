<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // "?->" mencegah error bila user null (tamu); tamu juga dianggap bukan admin.
        if (! $request->user()?->isAdmin()) {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockUpLoadDuringProcessing // It's a middleware registerd in bootstrap/app.php, responsible to block upload from 12AM to 4AM
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $now = Carbon::now();

        $processingStartsAt = $now->copy()->startOfDay();
        $processingEndsAt = $now->copy()->startOfDay()->addHours(4);

        $processingWindow = $now->between(
            $processingStartsAt,
            $processingEndsAt,
            true
        );

        if ($processingWindow) {
            return back()->with(
                'error',
                'Document uploads are unavailable from 12:00 AM until 4:00 AM while applications are being processed.'
            );
        }

        return $next($request);
    }
}

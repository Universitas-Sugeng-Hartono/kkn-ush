<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LargeFileUploadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set PHP configuration for large file uploads
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '25M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');
        ini_set('memory_limit', '256M');
        ini_set('max_file_uploads', '20');
        ini_set('file_uploads', 'On');
        ini_set('max_input_vars', '3000');
        ini_set('max_input_nesting_level', '64');

        // Set headers for large file uploads
        $response = $next($request);
        
        if ($request->isMethod('POST') && ($request->hasFile('photos') || $request->hasFile('attachments') || $request->hasFile('foto'))) {
            $response->headers->set('X-Accel-Buffering', 'no');
            $response->headers->set('Connection', 'close');
        }

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectMobileDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->query('mode') === 'desktop') {
            session(['device_override' => 'desktop', 'is_mobile_device' => false]);
        } elseif ($request->query('mode') === 'mobile') {
            session(['device_override' => 'mobile', 'is_mobile_device' => true]);
        } elseif (session('device_override') === 'desktop') {
            session(['is_mobile_device' => false]);
        } elseif (session('device_override') === 'mobile') {
            session(['is_mobile_device' => true]);
        } else {
            $userAgent = $request->header('User-Agent');
            $isMobile = $this->isMobileDevice($userAgent);
            session(['is_mobile_device' => $isMobile]);
        }
        
        return $next($request);
    }

    /**
     * Deteksi apakah device adalah mobile
     */
    private function isMobileDevice($userAgent): bool
    {
        // Desktop browsers yang tidak boleh terdeteksi sebagai mobile
        $desktopBrowsers = [
            'Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Internet Explorer'
        ];
        
        // Check jika ini adalah desktop browser
        foreach ($desktopBrowsers as $browser) {
            if (stripos($userAgent, $browser) != false && 
                stripos($userAgent, 'Mobile') === false &&
                stripos($userAgent, 'Android') === false &&
                stripos($userAgent, 'iPhone') === false &&
                stripos($userAgent, 'iPad') === false) {
                return false;
            }
        }

        // Mobile keywords
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone', 'BlackBerry',
            'webOS', 'iPod', 'Opera Mini', 'IEMobile', 'Mobile Safari'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) != false) {
                return true;
            }
        }

        return false;
    }
} 
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Detect device type from User-Agent
        $userAgent = $request->header('User-Agent');
        $isMobile = $this->detectMobileFromUserAgent($userAgent);
        
        // Store device info in session
        $request->session()->put('is_mobile_device', $isMobile);
        $request->session()->put('device_info', [
            'is_mobile' => $isMobile,
            'is_tablet' => false,
            'is_desktop' => !$isMobile,
            'screen_width' => 0,
            'screen_height' => 0,
            'user_agent' => $userAgent,
            'detected_at' => now()
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Detect mobile device from User-Agent string
     */
    private function detectMobileFromUserAgent($userAgent)
    {
        if (empty($userAgent)) {
            return false;
        }

        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone', 'BlackBerry',
            'Opera Mini', 'IEMobile', 'Mobile Safari', 'Mobile Chrome'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) != false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

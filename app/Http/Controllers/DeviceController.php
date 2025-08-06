<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeviceController extends Controller
{
    /**
     * Update device information from client-side detection
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_mobile' => 'boolean',
            'is_tablet' => 'boolean',
            'is_desktop' => 'boolean',
            'screen_width' => 'integer|min:1',
            'screen_height' => 'integer|min:1',
            'user_agent' => 'string'
        ]);

        // Store device info in session
        Session::put('device_info', [
            'is_mobile' => $validated['is_mobile'],
            'is_tablet' => $validated['is_tablet'],
            'is_desktop' => $validated['is_desktop'],
            'screen_width' => $validated['screen_width'],
            'screen_height' => $validated['screen_height'],
            'user_agent' => $validated['user_agent'],
            'detected_at' => now()
        ]);

        // Update the main mobile detection flag
        Session::put('is_mobile_device', $validated['is_mobile']);

        return response()->json([
            'success' => true,
            'message' => 'Device info updated successfully',
            'device_info' => Session::get('device_info')
        ]);
    }

    /**
     * Get current device information
     */
    public function info()
    {
        $deviceInfo = Session::get('device_info', []);
        
        return response()->json([
            'device_info' => $deviceInfo,
            'is_mobile_device' => Session::get('is_mobile_device', false)
        ]);
    }

    /**
     * Force mobile view (for testing)
     */
    public function forceMobile()
    {
        Session::put('is_mobile_device', true);
        Session::put('device_info', [
            'is_mobile' => true,
            'is_tablet' => false,
            'is_desktop' => false,
            'screen_width' => 375,
            'screen_height' => 667,
            'user_agent' => 'Mobile Test',
            'detected_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Forced mobile view'
        ]);
    }

    /**
     * Force desktop view (for testing)
     */
    public function forceDesktop()
    {
        Session::put('is_mobile_device', false);
        Session::put('device_info', [
            'is_mobile' => false,
            'is_tablet' => false,
            'is_desktop' => true,
            'screen_width' => 1920,
            'screen_height' => 1080,
            'user_agent' => 'Desktop Test',
            'detected_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Forced desktop view'
        ]);
    }

    /**
     * Reset device detection to automatic
     */
    public function reset()
    {
        Session::forget('device_info');
        Session::forget('is_mobile_device');

        return response()->json([
            'success' => true,
            'message' => 'Device detection reset to automatic'
        ]);
    }
} 
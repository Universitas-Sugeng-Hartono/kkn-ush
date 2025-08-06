# Device Detection System

## Overview
Sistem deteksi device yang memungkinkan aplikasi untuk mendeteksi jenis device (mobile, tablet, desktop) secara real-time dan menampilkan tampilan yang sesuai.

## Fitur Utama

### 1. Real-time Device Detection
- Deteksi otomatis berdasarkan ukuran layar, touch capability, orientasi, dan pixel ratio
- Update real-time saat resize window atau perubahan orientasi
- Penyimpanan informasi device di session untuk konsistensi

### 2. CSS Media Queries
- `.mobile-only` - Elemen yang hanya tampil di mobile
- `.desktop-only` - Elemen yang hanya tampil di desktop
- Device-specific classes: `.mobile-device`, `.tablet-device`, `.desktop-device`

### 3. JavaScript Device Detector
- Class `DeviceDetector` untuk deteksi device
- Event listeners untuk resize dan orientation change
- AJAX update ke server untuk menyimpan device info

### 4. Server-side Support
- `DeviceController` untuk menangani device info
- Session storage untuk device information
- API endpoints untuk force mobile/desktop view

## Implementasi

### 1. Middleware
```php
// app/Http/Middleware/DetectMobileDevice.php
// Mendeteksi device berdasarkan User-Agent
```

### 2. Controller
```php
// app/Http/Controllers/DeviceController.php
// Menangani update device info dan force view
```

### 3. Routes
```php
Route::post('/device/update', [DeviceController::class, 'update']);
Route::get('/device/info', [DeviceController::class, 'info']);
Route::post('/device/force-mobile', [DeviceController::class, 'forceMobile']);
Route::post('/device/force-desktop', [DeviceController::class, 'forceDesktop']);
Route::post('/device/reset', [DeviceController::class, 'reset']);
Route::get('/device/test', function() { return view('device-test'); });
```

### 4. CSS Classes
```css
/* Mobile Detection CSS */
.mobile-only { display: none; }
.desktop-only { display: block; }

@media (max-width: 768px) {
    .mobile-only { display: block; }
    .desktop-only { display: none; }
}

@media (hover: none) and (pointer: coarse) {
    .mobile-only { display: block; }
    .desktop-only { display: none; }
}
```

### 5. JavaScript Device Detector
```javascript
class DeviceDetector {
    constructor() {
        this.isMobile = false;
        this.isTablet = false;
        this.isDesktop = false;
        this.init();
        this.bindEvents();
    }
    
    detectDevice() {
        // Logic untuk deteksi device
    }
    
    updateSession() {
        // AJAX update ke server
    }
}
```

## Kriteria Deteksi Device

### Mobile Device
- Screen width ≤ 768px
- Touch capability tanpa hover
- Portrait orientation dengan width ≤ 768px
- Pixel ratio ≥ 2

### Tablet Device
- Screen width 768px - 1024px
- Touch capability dengan hover

### Desktop Device
- Semua device yang bukan mobile atau tablet

## Penggunaan

### 1. CSS Classes
```html
<div class="mobile-only">
    <!-- Konten hanya untuk mobile -->
</div>

<div class="desktop-only">
    <!-- Konten hanya untuk desktop -->
</div>
```

### 2. JavaScript
```javascript
// Mendapatkan info device
const deviceInfo = getDeviceInfo();
console.log(deviceInfo.isMobile); // true/false
console.log(deviceInfo.width); // screen width
console.log(deviceInfo.height); // screen height
```

### 3. Server-side
```php
// Di controller
if (Session::get('is_mobile_device')) {
    return view('mobile.dashboard');
} else {
    return view('dashboard');
}
```

## Testing

### 1. Device Test Page
Akses `/device/test` untuk testing fitur device detection:
- Real-time device info display
- Session info display
- Force mobile/desktop view
- Responsive test cards
- Screen information

### 2. Browser Developer Tools
- Resize browser window untuk test responsive
- Toggle device toolbar untuk simulate mobile
- Test orientation change

### 3. Real Device Testing
- Test di smartphone untuk mobile view
- Test di tablet untuk tablet view
- Test di desktop untuk desktop view

## Mobile App Mode

### Fitur Khusus Mobile
1. **Dashboard Mobile**
   - Quick actions dengan icon besar
   - Statistics cards yang compact
   - Recent activities list
   - Bottom navigation

2. **Logbook Mobile**
   - Drag & drop photo upload
   - Camera capture integration
   - Photo preview dengan modal
   - Form validation real-time

3. **Attendance Mobile**
   - Current time display
   - Geolocation capture
   - Camera photo capture
   - Location validation

4. **Notifications Mobile**
   - Badge indicators
   - Action buttons
   - Statistics display
   - Settings panel

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DeviceController.php
│   │   ├── DashboardController.php (updated)
│   │   ├── LogbookController.php (updated)
│   │   ├── AttendanceController.php (updated)
│   │   └── StudentNotificationController.php (updated)
│   └── Middleware/
│       └── DetectMobileDevice.php
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php (updated)
│   │   └── mobile-app.blade.php
│   ├── mobile/
│   │   ├── dashboard.blade.php
│   │   ├── logbooks/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── show.blade.php
│   │   ├── attendance/
│   │   │   ├── index.blade.php
│   │   │   └── create.blade.php
│   │   └── notifications/
│   │       └── index.blade.php
│   ├── dashboard.blade.php (updated)
│   └── device-test.blade.php
routes/
└── web.php (updated)
```

## API Endpoints

### Device Info
- `POST /device/update` - Update device info
- `GET /device/info` - Get current device info
- `POST /device/force-mobile` - Force mobile view
- `POST /device/force-desktop` - Force desktop view
- `POST /device/reset` - Reset to auto detection

## Browser Support

### Modern Browsers
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

### Mobile Browsers
- Chrome Mobile
- Safari Mobile
- Firefox Mobile
- Samsung Internet

## Performance Considerations

1. **Debounced Resize Events** - 250ms delay untuk menghindari terlalu banyak update
2. **Lazy Loading** - Device detection hanya dijalankan saat diperlukan
3. **Session Storage** - Device info disimpan di session untuk mengurangi request
4. **CSS-only Detection** - Media queries untuk deteksi cepat tanpa JavaScript

## Troubleshooting

### Common Issues
1. **Device tidak terdeteksi dengan benar**
   - Check User-Agent string
   - Verify screen size detection
   - Test touch capability

2. **Mobile view tidak muncul**
   - Check session storage
   - Verify middleware registration
   - Test force mobile function

3. **CSS classes tidak bekerja**
   - Check media query syntax
   - Verify CSS specificity
   - Test responsive breakpoints

### Debug Tools
1. **Device Test Page** - `/device/test`
2. **Browser Console** - Check JavaScript errors
3. **Network Tab** - Monitor AJAX requests
4. **Application Tab** - Check session storage

## Future Enhancements

1. **Advanced Device Detection**
   - Device fingerprinting
   - Browser capability detection
   - Performance metrics

2. **Progressive Enhancement**
   - Offline support
   - Service worker integration
   - Push notifications

3. **Analytics Integration**
   - Device usage tracking
   - Performance monitoring
   - User behavior analysis 
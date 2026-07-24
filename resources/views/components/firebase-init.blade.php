@auth
<script type="module">
    // Import Firebase SDK (V9 Modular)
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
    import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging.js";

    // Your web app's Firebase configuration
    // IMPORTANT: Replace these values with your actual Firebase config
    const firebaseConfig = {
        apiKey: "{{ config('services.firebase.api_key') }}",
        authDomain: "{{ config('services.firebase.auth_domain') }}",
        projectId: "{{ config('services.firebase.project_id') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket') }}",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
        appId: "{{ config('services.firebase.app_id') }}"
    };

    // Initialize Firebase
    let app, messaging;
    try {
        app = initializeApp(firebaseConfig);
        messaging = getMessaging(app);

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js').then((registration) => {
                
                const requestAndGetToken = () => {
                    Notification.requestPermission().then((permission) => {
                        if (permission === 'granted') {
                            getToken(messaging, { 
                                vapidKey: "{{ config('services.firebase.vapid_key') }}",
                                serviceWorkerRegistration: registration
                            }).then((currentToken) => {
                                if (currentToken) {
                                    sendTokenToServer(currentToken);
                                }
                            }).catch((err) => {
                                console.error('Error retrieving token: ', err);
                            });
                        } else {
                            // User blocked the prompt native. Don't show annoying popup, just log.
                            console.log('User denied native notification prompt.');
                        }
                    });
                };

                if (Notification.permission === 'granted') {
                    // Already granted, silently get token
                    requestAndGetToken();
                } else if (Notification.permission === 'default') {
                    // Only ask once per session or device using localStorage
                    const hasPrompted = localStorage.getItem('has_prompted_push');
                    if (!hasPrompted) {
                        let notifHtml = '<div class="text-start" style="font-size: 15px;"><p>Aktifkan notifikasi untuk mendapatkan pemberitahuan penting secara instan dari sistem.</p></div>';
                        
                        @role('mahasiswa')
                        notifHtml = '<div class="text-start" style="font-size: 15px;"><p>Aktifkan notifikasi untuk mendapatkan update <i>real-time</i>:</p><ul class="text-muted"><li>Pemberitahuan saat Logbook atau Absensi Anda disetujui/ditolak.</li><li>Mengetahui saat ada pengumuman penting.</li></ul></div>';
                        @endrole
                        
                        @role('dpl')
                        notifHtml = '<div class="text-start" style="font-size: 15px;"><p>Aktifkan notifikasi untuk kemudahan pemantauan <i>real-time</i>:</p><ul class="text-muted"><li>Langsung tahu saat mahasiswa mengirimkan Logbook baru.</li><li>Pemberitahuan instan saat mahasiswa melakukan Absensi.</li></ul></div>';
                        @endrole

                        if (window.Swal) {
                            Swal.fire({
                                title: 'Izinkan Notifikasi?',
                                html: notifHtml,
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Izinkan',
                                cancelButtonText: 'Nanti'
                            }).then((result) => {
                                localStorage.setItem('has_prompted_push', 'true');
                                if (result.isConfirmed) {
                                    requestAndGetToken();
                                }
                            });
                        } else {
                            localStorage.setItem('has_prompted_push', 'true');
                            requestAndGetToken();
                        }
                    }
                } else {
                    // Permission denied previously. Do not nag the user.
                    console.log('Notification permission has been denied previously.');
                }

            }).catch(function(err) {
                console.log('Service worker registration failed, error:', err);
            });
        }

        // Handle incoming messages when the app is in the foreground
        onMessage(messaging, (payload) => {
            console.log('Message received. ', payload);
            
            // You can use a library like SweetAlert2 or Toastr to show a nice notification
            if (window.Swal) {
                Swal.fire({
                    title: payload.notification.title,
                    text: payload.notification.body,
                    icon: 'info',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    toast: true
                });
            } else {
                alert(payload.notification.title + "\n" + payload.notification.body);
            }
        });

    } catch (error) {
        console.error("Firebase Initialization Error", error);
    }

    function sendTokenToServer(token) {
        fetch('{{ route("fcm-token.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token })
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal terhubung ke server');
            return response.json();
        })
        .then(data => console.log('Token saved:', data.message))
        .catch(error => {
            console.error('Error sending token to server:', error);
            if (window.Swal) Swal.fire('Error', 'Gagal menyimpan token notifikasi ke server.', 'error');
        });
    }
</script>
@endauth

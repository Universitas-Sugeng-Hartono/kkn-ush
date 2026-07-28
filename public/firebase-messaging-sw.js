importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

// Config from backend or hardcoded here if needed
// You will need to replace this config with your actual config
// It's recommended to dynamically inject this if possible, but for SW it's often hardcoded
firebase.initializeApp({
  apiKey: "AIzaSyCEUlrn5OmlzQrcBA0es_ySfsxOYS9QbRQ",
  authDomain: "kkn-ush-7bff2.firebaseapp.com",
  projectId: "kkn-ush-7bff2",
  storageBucket: "kkn-ush-7bff2.firebasestorage.app",
  messagingSenderId: "700312096616",
  appId: "1:700312096616:web:9f03e392731400bb1d50dd"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  if (payload.notification) {
    // If the payload already has a notification object, the Firebase SDK automatically displays it.
    // Calling showNotification here will result in duplicates.
    console.log('[firebase-messaging-sw.js] Notification handled automatically by SDK.');
    return;
  }
  
  // Only handle data-only messages manually
  const notificationTitle = payload.data.title || 'Notification';
  const notificationOptions = {
    body: payload.data.body || '',
    icon: '/images/ush.png',
    badge: '/images/ush.png',
    image: payload.data.banner_image || undefined,
    data: payload.data || {}
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      for (const client of clientList) {
        if (client.url && 'focus' in client) {
          if (client.url.includes(targetUrl) || targetUrl === '/') {
            return client.focus();
          }
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});

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
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/logo.png' // Pastikan logo ini ada di folder public
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});

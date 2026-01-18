/* firebase-messaging-sw.js (en la RAÍZ junto a index.php) */

importScripts("https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js");


firebase.initializeApp({
    apiKey: "AIzaSyAoGSevzD7Xk-gQF2Ag9bRYxFn25PBz8Lk",
    authDomain: "sistema-push-8284a.firebaseapp.com",
    projectId: "sistema-push-8284a",
    storageBucket: "sistema-push-8284a.firebasestorage.app",
    messagingSenderId: "614277206328",
    appId: "1:614277206328:web:f91b03e7a017db122971cd"
});

const messaging = firebase.messaging();

// Notificaciones en background (cuando no está abierta la pestaña)
messaging.onBackgroundMessage((payload) => {
    const title = payload?.notification?.title || "Notificación";
    const options = {
        body: payload?.notification?.body || "",
        icon: payload?.notification?.icon || undefined,
        data: payload?.data || {}
    };
    self.registration.showNotification(title, options);
});

// Activación inmediata
self.addEventListener("install", () => self.skipWaiting());
self.addEventListener("activate", (event) => event.waitUntil(self.clients.claim()));

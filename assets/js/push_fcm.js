// assets/js/push_fcm.js

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";


const firebaseConfig = {

  apiKey: "AIzaSyAoGSevzD7Xk-gQF2Ag9bRYxFn25PBz8Lk",
  authDomain: "sistema-push-8284a.firebaseapp.com",
  projectId: "sistema-push-8284a",
  storageBucket: "sistema-push-8284a.firebasestorage.app",
  messagingSenderId: "614277206328",
  appId: "1:614277206328:web:f91b03e7a017db122971cd"
};

const VAPID_KEY = "BMg3Gv0Lg6EEl2rD16_pcL6rphPoYhmB-CcJIaHi0a24VunQl33u4P3P4KHcKI6Vtc2OV6g8bmpJw9XWGHT1tiQ";


if (!window.__PUSH_FCM_INIT__) {
  window.__PUSH_FCM_INIT__ = true;

  const app = initializeApp(firebaseConfig);
  const messaging = getMessaging(app);


  window.activarNotificaciones = async function activarNotificaciones() {
    try {
      if (!("serviceWorker" in navigator)) {
        alert("Este navegador no soporta Service Worker.");
        return;
      }

      if (!window.BASE_URL) {
        console.error("BASE_URL no está definido. Revisa footer.php");
        alert("Error: BASE_URL no definido.");
        return;
      }


      const swReg = await navigator.serviceWorker.register(
        window.BASE_URL + "firebase-messaging-sw.js"
      );
      await navigator.serviceWorker.ready;


      const permiso = await Notification.requestPermission();
      if (permiso !== "granted") {
        alert("Permiso de notificaciones denegado.");
        return;
      }

      // Obtener token
      const token = await getToken(messaging, {
        vapidKey: VAPID_KEY,
        serviceWorkerRegistration: swReg
      });

      if (!token) {
        alert("No se pudo generar el token.");
        return;
      }

      console.log("FCM TOKEN:", token);

      // Enviar token al backend 
      /*
      const resp = await fetch(window.BASE_URL + "push/guardar_token", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          "X-Requested-With": "XMLHttpRequest"
        },
        body: "token=" + encodeURIComponent(token)
      });

      const data = await resp.json();
      console.log("guardar_token =>", data);

      if (data.status) {
        alert("✅ Notificaciones activadas correctamente.");
      } else {
        alert("⚠️ No se pudo guardar el token: " + (data.message || ""));
      }
    } catch (err) {
      console.error("ERROR activarNotificaciones:", err);
      alert("Error activando notificaciones. Revisa consola.");
    } */

    // Ini 8/4/26 subiendo a un serviddor 
      const resp = await fetch(window.BASE_URL + "push/guardar_token", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          "X-Requested-With": "XMLHttpRequest"
        },
        body: "token=" + encodeURIComponent(token)
      });

      const raw = await resp.text();
      console.log("RESPUESTA RAW guardar_token:", raw);

      let data;
      try {
        data = JSON.parse(raw);
      } catch (e) {
        throw new Error("La respuesta del servidor no es JSON válido: " + raw);
      }

      console.log("guardar_token =>", data);

      if (data.status) {
        alert("✅ Notificaciones activadas correctamente.");
      } else {
        alert("⚠️ No se pudo guardar el token: " + (data.message || ""));
      }
    } catch (err) {
      console.error("ERROR activarNotificaciones:", err);
      alert("Error activando notificaciones. Revisa consola.");
    }
    // fin 8/4/26 subiendo a un serviddor 

  };


  onMessage(messaging, (payload) => {
    console.log("PUSH FOREGROUND payload:", payload);

    try {
      const title = payload?.notification?.title || "Notificación";
      const options = {
        body: payload?.notification?.body || "",
        icon: payload?.notification?.icon || undefined
      };
      new Notification(title, options);
    } catch (e) {
      console.warn("No se pudo mostrar notificación en foreground:", e);
    }
  });

}

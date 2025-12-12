// resources/js/echo.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Use Vite envs (import.meta.env)
const key = import.meta.env.VITE_PUSHER_APP_KEY;
const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';

if (!key) {
  console.warn('VITE_PUSHER_APP_KEY missing — realtime disabled.');
  // still export a no-op Echo to avoid errors
  window.Echo = null;
} else {
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: key,
    cluster: cluster,
    forceTLS: true,        // use TLS for managed Pusher
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
  });

  // notify inline scripts that Echo is ready
  window.dispatchEvent(new Event("echo-ready"));
}

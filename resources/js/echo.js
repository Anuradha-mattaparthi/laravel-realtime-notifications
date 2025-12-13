import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const key = import.meta.env.VITE_PUSHER_APP_KEY;
const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';

if (!key) {
  console.warn('VITE_PUSHER_APP_KEY missing — realtime disabled.');
  window.Echo = null;
} else {
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: key,
    cluster: cluster,
    forceTLS: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
  });

  window.dispatchEvent(new Event("echo-ready"));
}

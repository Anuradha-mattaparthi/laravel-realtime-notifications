import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

console.log("KEY:", import.meta.env.VITE_PUSHER_APP_KEY);

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
});

// Let other JS know Echo is ready
window.dispatchEvent(new Event("echo-ready"));

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

let url = window.location.href;

let lastSegment = url.match(/\/([^\/]+)\/?$/)[1];
window.Echo.channel("cluster-job-"+lastSegment+"-channel")
    .listen("sendClusterjobdata", (event) => {
    console.log(event);
    $(".cluster-job-log").append("<h6 class='h6 text-white font-monospace'>"+event.message+"</h6>");
    $('.cluster-job-log').scrollTop($('.cluster-job-log')[0].scrollHeight);
});
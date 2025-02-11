import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from "fs";

let envdata=fs.readFileSync("/var/www/html/.env", 'utf8');
const appUrlMatch = envdata.match(/^APP_URL=(.+)$/m);
let appUrl = appUrlMatch[1].trim();
let modifiedUrl = `api.${appUrl.replace(/^https?:\/\//, '').replace(/\/$/, '')}`;


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: modifiedUrl,
        https: {
            cert: fs.readFileSync("/opt/certs/fullchain.pem"),
            key: fs.readFileSync("/opt/certs/privkey.pem"),
          },
        cors: ["*"]
        // port: 80,
      },
});

server {
        listen 80 proxy_protocol;
        server_name example.com;
        server_name www.example.com;

        location / {
                proxy_pass http://application:80;
                proxy_http_version 1.1;
                proxy_set_header Host $host;
                proxy_set_header X-Forwarded-Proto $scheme;
                proxy_set_header X-Forwarded-Host $server_name;
                proxy_set_header X-Real-IP $proxy_protocol_addr;
                proxy_set_header X-Forwarded-For $proxy_protocol_addr;
                proxy_set_header Upgrade $http_upgrade;
                proxy_set_header Connection "upgrade";
                return 301 https://$host$request_uri;
        }
}


server {
        listen 443 ssl http2 proxy_protocol;
        server_name example.com;
        server_name www.example.com;
        ssl_stapling on;
        ssl_stapling_verify on;
        ssl_certificate_key /usr/local/psa/var/modules/letsencrypt/etc/live/example.com/privkey.pem;
        ssl_certificate /usr/local/psa/var/modules/letsencrypt/etc/live/example.com/fullchain.pem;
        ssl_prefer_server_ciphers on;
        ssl_protocols  TLSv1.2 TLSv1.3;

        location / {
                proxy_pass http://application:80;
                proxy_http_version 1.1;
                proxy_set_header Host $host;
                proxy_set_header X-Forwarded-Proto $scheme;
                proxy_set_header X-Forwarded-Host $server_name;
                proxy_set_header X-Real-IP $proxy_protocol_addr;
                proxy_set_header X-Forwarded-For $proxy_protocol_addr;
        }
}
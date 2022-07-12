#!/bin/bash

echo "Staring nginx"
envsubst '${NGINX_HOST} ${NGINX_PORT}' < /etc/nginx/helper/nginx-config-docker.template > /etc/nginx/conf.d/default.conf

nginx -g 'daemon off;'
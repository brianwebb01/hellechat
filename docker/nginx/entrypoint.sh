#!/bin/sh

export GOTIFY_APP_PORT
export APP_DOMAIN

envsubst '${GOTIFY_APP_PORT} ${APP_DOMAIN}' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf

exec "$@"
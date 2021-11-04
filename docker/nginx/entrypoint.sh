#!/bin/sh

export GOTIFY_APP_PORT

envsubst '${GOTIFY_APP_PORT}' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf

exec "$@"
#!/bin/sh

mkdir -p storage/logs \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         bootstrap/cache

chown -R appuser:appgroup storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

exec "$@"

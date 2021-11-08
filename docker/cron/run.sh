while [ true ]
do
    cd /var/www && php artisan schedule:run --verbose --no-interaction >> /dev/stdout 2>&1 &
    sleep 60
done
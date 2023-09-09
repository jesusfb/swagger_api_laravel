cd /app  
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0

EXPOSE 80

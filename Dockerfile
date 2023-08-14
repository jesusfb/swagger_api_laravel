#FROM php:8.1-alpine


#RUN apk update 
#RUN curl -sS https://getcomposer.org/installer | php -- --version=2.4.3 --install-dir=/usr/local/bin --filename=composer


#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#COPY . /var/www/html
#WORKDIR /var/www/html

#COPY . .
#RUN composer install
#RUN php artisan config:clear
#RUN php artisan migrate

#CMD ["php","artisan","serve","--host=0.0.0.0"]

FROM php:8.1-fpm

# Add dependencies
RUN apt-get update -y && apt-get install -y openssl libpng-dev libxml2-dev curl cron git libzip-dev zip unzip

# Install php extensions
RUN docker-php-ext-install pdo mbstring gd xml pdo_mysql zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app/

WORKDIR /app

RUN chown -R $USER:www-data /app/storage
RUN chown -R $USER:www-data /app/bootstrap/cache

RUN chmod -R 775 /app/storage
RUN chmod -R 775 /app/bootstrap/cache

# Install composer dependencies
RUN composer install

RUN php artisan optimize

#RUN php artisan migrate --seed

RUN crontab -l | { cat; echo "* * * * * php /app/artisan schedule:run >> /dev/null 2>&1"; } | crontab -

STOPSIGNAL SIGTERM

CMD ["php-fpm"]

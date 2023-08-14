FROM php:8.1-alpine


RUN apk update 
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.4.3 --install-dir=/usr/local/bin --filename=composer


#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#COPY . /var/www/html
#WORKDIR /var/www/html

COPY . .
RUN composer install
#RUN php artisan config:clear
#RUN php artisan migrate

CMD bash -c "php artisan migrate"
CMD ["php","artisan","serve","--host=0.0.0.0"]


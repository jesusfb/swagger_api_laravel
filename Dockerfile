FROM php:8.1-alpine

RUN apk update 
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.4.3 --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update \
&& apt-get -y --no-install-recommends install  php7.3-mysql php7.3-intl mysql-client php-common openssl zip unzip git \
&& apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
RUN docker-php-ext-install pdo mbstring pdo_mysql

COPY . .
RUN composer install

CMD ["php","artisan","serve","--host=0.0.0.0"]


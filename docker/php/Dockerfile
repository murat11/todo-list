FROM php:7.2-fpm
MAINTAINER Murat Erkenov <murat@narsana.ru>

RUN apt-get update && apt-get install -y --no-install-recommends libssl-dev libmcrypt-dev libxml2-dev libzip-dev git libcurl4-openssl-dev ssh unzip

RUN docker-php-ext-install zip pdo pdo_mysql
RUN pecl install xdebug
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY conf.d/* /usr/local/etc/php/conf.d/

CMD ["php-fpm"]

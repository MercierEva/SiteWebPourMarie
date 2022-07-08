FROM php:8.0-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get upgrade -y
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY zz-app.ini /usr/local/etc/php/conf.d/zz-app.ini
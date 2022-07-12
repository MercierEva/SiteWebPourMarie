FROM php:8.0-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get upgrade -y
#RUN echo "ServerName 127.0.0.1" >> /etc/apache2/apache2.conf
COPY zz-app.ini /usr/local/etc/php/conf.d/zz-app.ini


ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid
ENV APACHE_SERVER_NAME localhost:8000

COPY apache-conf /etc/apache2/apache2.conf

RUN a2enmod rewrite
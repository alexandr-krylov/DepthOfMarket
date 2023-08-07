FROM php:8.2
WORKDIR /var/www/app
RUN apt-get update \
    && apt-get -y upgrade \
    && apt-get -y install libzip-dev git unzip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN docker-php-ext-install zip pdo_mysql

CMD ["php", "-S", "0.0.0.0:8000"]
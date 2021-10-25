FROM php:8.0-fpm

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libxslt-dev

RUN docker-php-ext-install intl opcache pdo pdo_mysql xsl\
    && pecl install xdebug apcu redis\
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/app

#RUN curl -sS https://get.symfony.com/cli/installer | bash
#RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

#CMD ["/usr/local/bin/php", "/var/www/app/bin/console", "messenger:consume", "async", "-vv"]
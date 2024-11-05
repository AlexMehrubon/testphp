FROM php:8.3-fpm-alpine

# Обновление и установка необходимых пакетов
RUN apk update && apk add --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    curl \
    libxml2-dev \
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        gd \
        mbstring \
        xml

# Установка Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Настройка Xdebug
RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка рабочей директории
WORKDIR /var/www/src

# Установка PHPUnit через Composer
RUN composer require --dev phpunit/phpunit ^11.4
RUN composer install

# Установка PHPUnit через wget
RUN wget https://phar.phpunit.de/phpunit.phar \
    && chmod +x phpunit.phar \
    && mv phpunit.phar /usr/local/bin/phpunit

# Установка php-fpm как команды по умолчанию
CMD ["php-fpm"]
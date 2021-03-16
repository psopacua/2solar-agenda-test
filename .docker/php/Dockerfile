FROM php:7.4-fpm

# XDebug
RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug

# PDO
RUN docker-php-ext-install pdo pdo_mysql
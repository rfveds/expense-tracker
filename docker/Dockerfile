FROM php:8.3.8-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    vim \
    libicu-dev

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash \
    && apt-get install nodejs -y

# Instal composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure and install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql intl

# Install xdebug
RUN docker-php-source extract
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error_reporting.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini
RUN echo "xdebug.mode=develop,coverage,debug,trace" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.discover_client_host=1"  >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.var_display_max_children=128" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.var_display_max_data=512" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.var_display_max_depth=3" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m
RUN passwd -d dev

EXPOSE 9000
CMD ["php-fpm"]

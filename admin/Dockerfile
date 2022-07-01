FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
       libfreetype6-dev \
       libjpeg62-turbo-dev \
       libpng-dev \
       git \
       locales \
       jpegoptim optipng pngquant gifsicle \
       vim \
       curl \
       libzip-dev \
       libonig-dev \
       zip \
       unzip \
       mc \
   && docker-php-ext-configure zip \
   && docker-php-ext-install zip


# Set working directory
WORKDIR /var/www/admin


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl
RUN docker-php-ext-configure gd --enable-gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www/admin

RUN chown root:root /var/www
RUN chmod 755 /var/www/
RUN chown -R www:www /var/www
RUN chmod -R 774 /var/www

# Envs change
# COPY .env.example envs/.env

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/admin

# Change current user to www
USER www

# Set /storage /public permission to public
RUN chmod -R 777 storage/ public/

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

RUN cp .env.example .env
RUN composer install
RUN php artisan key:generate


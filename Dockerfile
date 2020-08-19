FROM php:7.4-fpm as codeine-builder

USER root

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libcurl4-openssl-dev pkg-config libssl-dev \
    libgraphicsmagick1-dev

RUN export MAKEFLAGS="-j $(grep -c ^processor /proc/cpuinfo)"

#RUN docker-php-ext-install mbstring zip exif pcntl
RUN docker-php-ext-install opcache      \
                            mbstring    \
                            zip         \
                            exif        \
                            pcntl
RUN pecl install mongodb-1.7.4      \
                 gmagick-beta       \
                 igbinary-stable    \
                 redis-stable

FROM php:7.4-fpm as codeine-app
USER root

ENV LANG="ru_RU.UTF-8"        \
    LANGUAGE="ru_RU.UTF-8"    \
    LC_ALL="ru_RU.UTF-8"

ENV PHP_OPCACHE_REVALIDATE_FREQ="0"             \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS="1"         \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000"   \
    PHP_OPCACHE_MEMORY_CONSUMPTION="256"        \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

COPY --from=codeine-builder /usr/local/lib/php/extensions/no-debug-non-zts-20190902/*.so  /usr/local/lib/php/extensions/no-debug-non-zts-20190902/

RUN apt-get update && apt-get install -y \
    locales \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    libgraphicsmagick-q16-3

# Set locale
RUN sed -i -e 's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=ru_RU.UTF-8

# Clear APT cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure php and php extension
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/

# Configure PHP-FPM
ENV PHP_FPM_PM="dynamic"                        \
    PHP_FPM_PM_MAX_CHILDREN="64"                \
    PHP_FPM_PM_START_SERVERS="16"               \
    PHP_FPM_PM_MIN_SPARE_SERVERS="16"           \
    PHP_FPM_PM_MAX_SPARE_SERVERS="32"           \
    PHP_FPM_PM_PROCESS_IDLE_TIMEOUT="16s"       \
    PHP_FPM_PM_MAX_REQUESTS="512"               \
    PHP_FPM_PM_STATUS_PATH="/status"

RUN sed -i 's/pm =.*/pm = \$\{PHP_FPM_PM\}/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^pm\.max_children =.*/pm\.max_children = \$\{PHP_FPM_PM_MAX_CHILDREN\}/' /usr/local/etc/php-fpm.d/www.conf  && \
    sed -i 's/^pm\.start_servers =.*/pm\.start_servers = \$\{PHP_FPM_PM_START_SERVERS\}/' /usr/local/etc/php-fpm.d/www.conf  && \
    sed -i 's/^pm\.min_spare_servers =.*/pm\.min_spare_servers = \$\{PHP_FPM_PM_MIN_SPARE_SERVERS\}/' /usr/local/etc/php-fpm.d/www.conf  && \
    sed -i 's/^pm\.max_spare_servers =.*/pm\.max_spare_servers = \$\{PHP_FPM_PM_MAX_SPARE_SERVERS\}/' /usr/local/etc/php-fpm.d/www.conf  && \
    sed -i 's/^;pm\.process_idle_timeout =.*/pm\.process_idle_timeout = \$\{PHP_FPM_PM_PROCESS_IDLE_TIMEOUT\}/' /usr/local/etc/php-fpm.d/www.conf  && \
    sed -i 's/^;pm\.max_requests =.*/pm\.max_requests = \$\{PHP_FPM_PM_MAX_REQUESTS\}/' /usr/local/etc/php-fpm.d/www.conf  && \
    sed -i 's/^;pm\.status_path =.*/pm\.status_path = \$\{PHP_FPM_PM_STATUS_PATH\}/' /usr/local/etc/php-fpm.d/www.conf

# Add user for  application
RUN if [ ! $(getent group www) ]; then groupadd -g 1000 www; fi
RUN if [ ! $(getent passwd www) ]; then useradd -u 1000 -ms /bin/bash -g www www; fi

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Composer
COPY --chown=www:www ./composer.json /var/www/codeine/

WORKDIR /var/www/codeine
RUN composer install

# Copy src to /var/www/
COPY --chown=www:www ./src /var/www/codeine

RUN if [ ! -d /var/www/codeine/Data ]; then mkdir /var/www/codeine/Data; fi
RUN chmod -R 777 /var/www/codeine/Data

RUN if [ ! -d /var/log/codeine ]; then mkdir /var/log/codeine; fi
RUN chmod -R 777 /var/log/codeine

RUN if [ ! -d /var/tmp/codeine ]; then mkdir /var/tmp/codeine; fi
RUN chown -R www:www /var/tmp/codeine

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

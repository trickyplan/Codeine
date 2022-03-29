FROM php:8.0-fpm as codeine-builder
LABEL maintainer="bergstein@trickyplan.com"

USER root

ENV MAKEFLAGS="-j$[$(nproc) + 1]"

# Install dependencies
RUN apt-get update -q && \
    apt-get install --no-install-recommends -yq build-essential libonig-dev locales libzip-dev zip unzip git curl  \
    libcurl4-openssl-dev pkg-config libssl-dev libpng-dev libfreetype6-dev libmagickwand-dev \
    libjpeg62-turbo-dev libyaml-dev && \
    rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install -j$(nproc) opcache && \
    docker-php-ext-install -j$(nproc) mbstring && \
    docker-php-ext-install -j$(nproc) zip && \
    docker-php-ext-install -j$(nproc) pcntl && \
    docker-php-ext-install -j$(nproc) exif && \
    pecl install yaml-2.2.2 && \
    pecl install mongodb-1.12.1 && \
    pecl install imagick-3.7.0 && \
    pecl install igbinary-3.2.7 && \
    pecl install redis-5.3.7 && \
    pecl install zstd-0.11.0

FROM php:8.0-fpm as codeine-app
USER root

COPY --from=codeine-builder /usr/local/lib/php/extensions/no-debug-non-zts-20200930/*.so  /usr/local/lib/php/extensions/no-debug-non-zts-20200930/

RUN apt-get update -q && \
    apt-get install --no-install-recommends -yq oathtool locales \
    libgraphicsmagick-q16-3 libmagickwand-6.q16-6 \
    unzip curl libyaml-0-2 git && \
    rm -rf /var/lib/apt/lists/*

# Copy PHP-FPM configs

COPY docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/
COPY docker/php-fpm.d/*.conf /usr/local/etc/php-fpm.d/

# Add user for  application
RUN if [ ! $(getent group codeine) ]; then groupadd -g 1000 codeine; fi
RUN if [ ! $(getent passwd codeine) ]; then useradd -u 1000 -ms /bin/bash -g codeine codeine; fi

# Composer

COPY --chown=codeine:codeine ./src /var/www/codeine/src
COPY --chown=codeine:codeine ./composer.json /var/www/codeine/
COPY --chown=codeine:codeine ./composer.lock /var/www/codeine/
COPY --chown=codeine:codeine ./index.php /var/www/codeine/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Composer Install
WORKDIR /var/www/codeine
RUN composer install

# Copy src to /var/www/
RUN chown -R codeine /var/www/codeine/vendor

RUN if [ ! -d /var/www/codeine/src/Data ]; then mkdir /var/www/codeine/src/Data; fi
RUN chmod -R 777 /var/www/codeine/src/Data

RUN if [ ! -d /var/log/codeine ]; then mkdir /var/log/codeine; fi
RUN chmod -R 777 /var/log/codeine

RUN if [ ! -d /var/tmp/codeine ]; then mkdir /var/tmp/codeine; fi
RUN chown -R codeine:codeine /var/tmp/codeine

# Change current user to codeine
USER codeine

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

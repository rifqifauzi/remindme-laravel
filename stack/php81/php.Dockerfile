# ref: https://github.com/phpdocker-io/base-images/blob/master/php/8.1/Dockerfile

FROM debian:11-slim

# Fixes some weird terminal issues such as broken clear / CTRL+L
ENV TERM=linux

# Ensure apt doesn't ask questions when installing stuff
ENV DEBIAN_FRONTEND=noninteractive

# apt-slim helper
COPY apt-slim /usr/local/bin/apt-slim
RUN chmod +x /usr/local/bin/apt-slim

# Debian php
RUN apt-slim -y install lsb-release ca-certificates curl unzip \
    && curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg \
    && sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'

# Install php extensions
RUN apt-slim -y --no-install-recommends install \
        php8.1-bcmath \
        php8.1-cli \
        php8.1-curl \
        php8.1-enchant \
        php8.1-gd \
        php8.1-gmp \
        php8.1-intl \
        php8.1-ldap \
        php8.1-mbstring \
        php8.1-readline \
        php8.1-xml \
        php8.1-zip \
        \
        php8.1-mysql \
        php8.1-pgsql \
        php8.1-redis \
        php8.1-sqlite3 \
        php8.1-sybase \
        \
        php8.1-fpm \
        \
        libxrender1 libxext6

# Use latest composer
RUN curl --progress-bar https://getcomposer.org/composer-stable.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Override default config
COPY z-overrides.conf /etc/php/8.1/fpm/pool.d/z-overrides.conf

# Writable pid file
RUN install -m 777 /dev/null /run/php-fpm.pid

# Set workdir
WORKDIR /var/www/src

# Map uid to regular user
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
RUN chown www-data:www-data /var/www
USER www-data


# Open up fcgi port
EXPOSE 9000
CMD ["/usr/sbin/php-fpm8.1", "-O" ]

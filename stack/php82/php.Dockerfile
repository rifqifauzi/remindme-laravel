# ref: https://github.com/phpdocker-io/base-images/blob/master/php/8.2/Dockerfile

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
        php8.2-bcmath \
        php8.2-cli \
        php8.2-curl \
        php8.2-enchant \
        php8.2-gd \
        php8.2-gmp \
        php8.2-intl \
        php8.2-ldap \
        php8.2-mbstring \
        php8.2-readline \
        php8.2-xml \
        php8.2-zip \
        \
        php8.2-mysql \
        php8.2-pgsql \
        php8.2-redis \
        php8.2-sqlite3 \
        php8.2-sybase \
        \
        php8.2-fpm \
        \
        libxrender1 libxext6

# Use latest composer
RUN curl --progress-bar https://getcomposer.org/composer-stable.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Override default config
COPY z-overrides.conf /etc/php/8.2/fpm/pool.d/z-overrides.conf

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
CMD ["/usr/sbin/php-fpm8.2", "-O" ]

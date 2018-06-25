FROM debian:buster

# Maintainer
MAINTAINER Olda Salek <mzk@mozektevidi.net>

RUN apt-get update \
    && apt-get install -y \
        curl \
        git \
        nano \
        wget \
        php7.2-cli \
        php7.2-intl \
        php7.2-xml \
        php7.2-mbstring \
        php7.2-zip \
        php7.2-gd \
        php7.2-mysqli \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Create directories
RUN mkdir -p /var/www/citicash/www

WORKDIR /var/www/citicash/www

RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#RUN composer global require "hirak/prestissimo:^0.3"

RUN php -v

# Run!
#CMD ["php", "-S", "0.0.0.0:8080", "./web/app_dev.php"]
CMD ["tail -f /dev/null"]

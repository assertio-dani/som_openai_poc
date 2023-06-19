FROM php:8.2-apache as base_image

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions  \
    @composer-2
#    amqp \
#    http \
#    intl \
#    pdo_mysql \
#    zip



# setup apache
COPY ./docker/conf/site.conf /etc/apache2/sites-available/som_ai.conf
RUN a2dissite 000-default.conf
RUN a2ensite som_ai.conf

RUN a2enmod headers \
 && a2enmod rewrite

RUN echo "ServerName som_ai" >> /etc/apache2/apache2.conf

# override PHP config
COPY ./docker/conf/custom.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www



FROM base_image as dev_image

RUN install-php-extensions xdebug

# copy config *after* installing extension
COPY docker/conf/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"



FROM base_image as pro_image
ENV APP_ENV=PRO
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions opcache

COPY . /var/www
RUN composer --no-ansi --no-interaction --optimize-autoloader --no-progress --no-dev install

RUN composer dump-env pro


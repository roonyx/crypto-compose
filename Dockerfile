FROM php:7.2-fpm-alpine
ARG TIMEZONE

# Install Composer and create User
RUN apk --update add --virtual build-deps openssl unzip curl && \
    addgroup -g 1001 -S cryptocompose && \
    adduser -D -S -u 1000 -G cryptocompose -h /home/cryptocompose cryptocompose && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apk del build-deps && \
    rm -rf /tmp/* /var/tmp/* /usr/share/man /tmp/* /var/tmp/* \
           /var/cache/apk/* /var/log/* ~/.cache

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini

#COPY src/composer* /

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql

COPY ./src /home/cryptocompose
COPY ./wait-for /home/cryptocompose

RUN cd /home/cryptocompose && chown -R cryptocompose:cryptocompose /home/cryptocompose \
    /usr/local/bin

WORKDIR /home/cryptocompose

USER cryptocompose

CMD ["php-fpm", "-F"]
FROM alpine:3.13

USER root

WORKDIR /var/www/localhost/htdocs/app

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./.docker /.docker

ARG DEPS="\
  bash \
  nano \
  curl \
  ca-certificates \
  openssl \
  wget \
  zip \
  unzip \
  dos2unix \
  apache2 \
  php8 \
  php8-apache2 \
  php8-bcmath \
  php8-bz2 \
  php8-cli \
  php8-common \
  php8-ctype \
  php8-curl \
  php8-dom \
  php8-exif \
  php8-fileinfo \
  php8-gd \
  php8-gettext \
  php8-gmp \
  php8-iconv \
  php8-imap \
  php8-intl \
  php8-json \
  php8-ldap \
  php8-mbstring \
  php8-openssl \
  php8-pdo \
  php8-pdo_dblib \
  php8-pdo_mysql \
  php8-pdo_odbc \
  php8-pdo_pgsql \
  php8-pdo_sqlite \
  php8-phar \
  php8-posix \
  php8-redis \
  php8-session \
  php8-simplexml \
  php8-sockets \
  php8-tokenizer \
  php8-xml \
  php8-xmlreader \
  php8-xmlwriter \
  php8-zip \
  php8-zlib \
"

RUN set -ex \
  && apk update \
  && apk add --no-cache $DEPS \
  && apk add --no-cache --repository=http://dl-cdn.alpinelinux.org/alpine/v3.9/main nodejs npm \
  && mkdir -p /run/apache2 \
  && ln -s /usr/bin/php8 /usr/bin/php \    
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=2.1.9 \
  && cp /.docker/configs/vhost.conf /etc/apache2/conf.d/vhost.conf \
  && cp /.docker/scripts/docker-entrypoint.sh /docker-entrypoint.sh \
  && dos2unix /docker-entrypoint.sh \
  && chmod +x /docker-entrypoint.sh \
  && rm -fr /var/cache/apk/*
	
EXPOSE 8080

ENTRYPOINT ["bash","/docker-entrypoint.sh"]
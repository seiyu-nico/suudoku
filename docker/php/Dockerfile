FROM php:7.4.2-fpm
LABEL maintainer "seiyu-nico <yuseiishii141+docker@gmail.com>"

RUN apt update \
    && apt -y install locales \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && rm -rf /var/lib/apt/lists/* \
    && echo "ja_JP.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen ja_JP.UTF-8 
COPY ./php.ini /usr/local/etc/php/
ENV LC_ALL ja_JP.UTF-8
WORKDIR /code
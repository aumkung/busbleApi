FROM php:7.2-fpm
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install bcmath pdo pdo_mysql iconv mbstring json opcache \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
# CMD ["php-fpm"]

# RUN apt-get -yqq update && apt-get install -yq \
#         libcurl4-openssl-dev \
#         autoconf \
#         build-essential \
#         libmcrypt-dev \
#         libfreetype6-dev \
#         libjpeg62-turbo-dev \
#         libpng-dev \
#         libpcre3-dev \
#     && docker-php-ext-install bcmath curl pdo pdo_mysql iconv mbstring json opcache \
#     && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
#     && docker-php-ext-install -j$(nproc) gd \
#     && pecl install xdebug \
#     && docker-php-ext-enable xdebug
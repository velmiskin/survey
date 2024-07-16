FROM php:8.2-fpm

#common soft
RUN apt-get -y update && apt-get -y install --fix-missing -y wget unzip autoconf pkg-config iputils-ping procps \
#zip
&& apt-get install -y libzip-dev && docker-php-ext-install zip \
#bcmath \
&& docker-php-ext-install bcmath \
#intl
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl \
#postgres
&& apt-get install -y libpq-dev \
&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
&& docker-php-ext-install pdo pdo_pgsql \

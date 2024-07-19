FROM php:8.2-fpm

ADD php.ini $PHP_INI_DIR/conf.d/php-ini-overrides.ini

#common soft
RUN apt-get -y update && apt-get -y install --fix-missing -y wget unzip autoconf pkg-config iputils-ping procps \
#xdebug
&& pecl install xdebug \
&& docker-php-ext-enable xdebug \
#composer
&& curl -sS https://getcomposer.org/installer | php \
&& mv composer.phar /usr/local/bin/composer \
&& chmod +x /usr/local/bin/composer \
#phpunit
&& wget -O phpunit https://phar.phpunit.de/phpunit-11.phar \
&& mv phpunit /usr/local/bin/phpunit \
&& chmod +x /usr/local/bin/phpunit \
&& echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.client_port = 9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.discover_client_host = 0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
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

FROM php:8.2-fpm

RUN apt-get -y update && apt-get -y install --fix-missing -y wget unzip autoconf pkg-config iputils-ping procps
ADD php.ini $PHP_INI_DIR/conf.d/php-ini-overrides.ini

#common soft
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions xdebug zip intl pdo_pgsql pdo bcmath \
#phpunit
&& wget -O phpunit https://phar.phpunit.de/phpunit-11.phar \
&& mv phpunit /usr/local/bin/phpunit \
&& chmod +x /usr/local/bin/phpunit \

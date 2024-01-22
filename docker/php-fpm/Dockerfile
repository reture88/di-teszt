#FROM php:8.3-rc-fpm
FROM php:8.2-fpm

COPY usr/ /usr

RUN apt-get update
RUN apt-get install -y unzip
RUN apt-get install -y git
RUN apt-get install -y mc

# https://github.com/docker-library/docs/tree/master/php#how-to-install-more-php-extensions
RUN docker-php-ext-install pdo_mysql
#RUN docker-php-ext-install mysqli

RUN curl -O -L https://getcomposer.org/download/latest-stable/composer.phar
RUN mv composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer

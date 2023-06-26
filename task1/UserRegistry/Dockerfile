FROM php:7.4-apache

# Install required dependencies
RUN apt-get update && \
    apt-get install -y libpng-dev && \
    apt-get install -y unzip && \
    apt-get install -y wget

WORKDIR /var/www/html

COPY . .

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install gd
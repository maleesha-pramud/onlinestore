FROM php:8.3-apache

# Install and enable the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

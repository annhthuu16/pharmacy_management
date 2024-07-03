FROM php:apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo_mysql
# Enable mod_rewrite for Apache
RUN a2enmod rewrite

WORKDIR /

COPY . .

ENV PORT=80

EXPOSE 80
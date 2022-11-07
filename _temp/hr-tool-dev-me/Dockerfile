FROM php:8.0.17-apache
#Install git
RUN apt-get update && \
    apt-get install -y git && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-install gd sockets 
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apt-get update \
    && apt-get install libldap2-dev -y \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install ldap
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get update && \
     apt-get install -y \
         libzip-dev \
         && docker-php-ext-install zip
RUN a2enmod rewrite

RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

COPY / /var/www/html/
RUN chmod 777 /var/www/html/compile
RUN chmod 777 /var/www/html/uploads
EXPOSE 80

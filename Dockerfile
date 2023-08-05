FROM php:8.0-apache
RUN rm /bin/sh && ln -s /bin/bash /bin/sh
RUN apt-get update\
    && apt-get upgrade -y\
    && apt-get install -y --no-install-recommends\
    git\
    unzip\
    libfreetype6-dev\
    libjpeg62-turbo-dev\
    libwebp-dev\
    libzip-dev\
    libicu-dev\
    libonig-dev\
    sqlite3\
    libsqlite3-dev\
    default-mysql-client-core\
    libicu-dev  \
    zlib1g-dev \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/* \
    && mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo_mysql bcmath gd intl zip
RUN curl -sSL https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 -o mhsendmail \
    && chmod +x mhsendmail \
    && mv mhsendmail /usr/local/bin/mhsendmail \
    && echo 'sendmail_path = "/usr/local/bin/mhsendmail --smtp-addr=mailhog:1025"' > /usr/local/etc/php/conf.d/sendmail.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN mkdir /app && mkdir /app/public && ln -s /app/public /var/www/html/app

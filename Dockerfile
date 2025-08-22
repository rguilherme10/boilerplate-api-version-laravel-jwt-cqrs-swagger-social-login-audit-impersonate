FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

# Instala dependências do sistema
RUN apk update && apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    curl-dev \
    oniguruma-dev \
    openssl-dev \
    sqlite-dev \
    zip \
    unzip \
    bash \
    git \
    autoconf \
    g++ \
    make

# Configura e instala extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        fileinfo \
        curl \
        gd \
        mbstring \
        zip

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia arquivos do projeto
COPY ./composer.json ./composer.lock ./
RUN composer install --no-dev --optimize-autoloader --verbose

COPY . .

# Permissões (descomente para produção)
# RUN chown -R www-data:www-data /var/www/html

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
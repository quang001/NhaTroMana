# ============================================
# Dockerfile cho PHP-FPM + Laravel
# ============================================
FROM php:8.2-fpm-alpine

# Cài đặt các thư viện hệ thống cần thiết
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    nodejs \
    npm

# Cài đặt PHP extensions cần thiết cho Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    xml

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn vào container
COPY ./app /var/www/html

# Cài đặt dependencies của Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction 2>/dev/null || true

# Phân quyền cho các thư mục của Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 9000 cho PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]

FROM php:8.2-cli

# Cài đặt các extension cần thiết
RUN apt-get update && apt-get install -y libonig-dev zip unzip libpq-dev && docker-php-ext-install pdo pdo_mysql mbstring

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tạo thư mục làm việc
WORKDIR /var/www

# Copy toàn bộ project vào container
COPY . .

# Cài đặt các package Laravel
RUN composer install && chmod -R 777 storage bootstrap/cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

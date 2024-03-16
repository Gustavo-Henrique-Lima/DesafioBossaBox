# Use a imagem oficial do PHP como base
FROM php:8.2.12

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www/html

# Atualiza os repositórios e instala as dependências necessárias
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Instala as extensões PHP necessárias
RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia os arquivos do projeto para o contêiner
COPY . .

# Instala as dependências do Composer
RUN composer install

# Expõe a porta 8000 do contêiner
EXPOSE 8000

# Comando padrão a ser executado quando o contêiner for iniciado
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

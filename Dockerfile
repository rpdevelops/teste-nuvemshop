FROM php:8.2-apache

# Habilita mod_rewrite
RUN a2enmod rewrite

# Copia seu código para dentro do container
COPY . /var/www/html/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

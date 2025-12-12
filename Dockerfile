# Base PHP + Apache
FROM php:8.2-apache

# Habilita mod_rewrite (importante p/ MVC e rotas)
RUN a2enmod rewrite

# Copia todos os arquivos do projeto para o Apache
COPY . /var/www/html/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta correta
EXPOSE 80

# Inicia o Apache
CMD ["apache2-foreground"]

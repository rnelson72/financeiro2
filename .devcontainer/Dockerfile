FROM mcr.microsoft.com/devcontainers/base:ubuntu

# Instala PHP, Apache e MySQL
RUN apt-get update && apt-get install -y \
  php \
  php-mysql \
  apache2 \
  mysql-server \
  && apt-get clean

# Habilita mod_rewrite e outras configs do Apache
RUN a2enmod rewrite

# Configura diretório raiz
RUN rm -rf /var/www/html && ln -s /workspaces/$(basename $PWD)/public /var/www/html
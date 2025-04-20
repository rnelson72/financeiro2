FROM mcr.microsoft.com/devcontainers/base:ubuntu

# Instala PHP, Apache e MySQL com dependências necessárias
RUN apt-get update && apt-get install -y \
  php \
  php-mysql \
  apache2 \
  mysql-server \
  && apt-get clean

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Ajusta diretório raiz do Apache
RUN rm -rf /var/www/html && ln -s /workspaces/$(basename $PWD)/public /var/www/html
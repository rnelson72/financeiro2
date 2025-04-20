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

# Garante permissões corretas para o socket do MySQL
RUN mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chmod 755 /var/run/mysqld

# Copia script SQL de inicialização
COPY init.sql /tmp/init.sql

# Aplica script SQL com MySQL iniciado
RUN service mysql start && \
    sleep 5 && \
    mysql -u root < /tmp/init.sql

FROM mcr.microsoft.com/devcontainers/base:ubuntu

RUN apt-get update && apt-get install -y --no-install-recommends \
  apt-utils \
  gnupg \
  php \
  php-cli \
  php-mysql \
  php8.1-pgsql \
  postgresql-client \
  php-dev \
  php-pear \
  apache2 \
  mysql-server \
  build-essential \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Permissões do MySQL
RUN mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chmod 755 /var/run/mysqld

COPY init.sql /tmp/init.sql

COPY ajustar_ambiente.sh /.devcontainer/ajustar_ambiente.sh
RUN chmod +x /.devcontainer/ajustar_ambiente.sh
FROM mcr.microsoft.com/devcontainers/base:ubuntu

# Instala dependências básicas, PHP, Apache, MySQL, e ferramentas de build/dev para PECL
RUN apt-get update && apt-get install -y --no-install-recommends \
  # Essenciais
  apt-utils \
  gnupg \
  # Servidores e PHP
  php \
  php-cli \
  php-mysql \
  php-dev \  # <-- Necessário para PECL e compilar extensões
  apache2 \
  mysql-server \
  # Ferramentas de build
  build-essential \ # <-- Necessário para compilar (PECL)
  # Limpeza
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# Agora que PHP e php-dev estão instalados, instala o Xdebug via PECL
RUN pecl install xdebug

# Cria manualmente o arquivo de configuração para habilitar Xdebug
# Substitui o 'docker-php-ext-enable' que pode não funcionar aqui
RUN echo "zend_extension=$(find /usr/lib/php -name xdebug.so)" > /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini && \
    echo "xdebug.mode=debug" >> /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini && \
    echo "xdebug.start_with_request=trigger" >> /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini && \
    echo "xdebug.client_port=9003" >> /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini && \
    # (Opcional) Log do Xdebug - Crie o diretório se for usar
    # echo "xdebug.log=/tmp/xdebug/xdebug.log" >> /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini && \
    # Habilita a configuração para Apache e CLI
    ln -s /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/apache2/conf.d/20-xdebug.ini && \
    ln -s /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/mods-available/xdebug.ini /etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/cli/conf.d/20-xdebug.ini

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Ajusta diretório raiz do Apache
# RUN rm -rf /var/www/html && ln -s /workspaces/$(basename $PWD)/public /var/www/html
# Remove o diretório/link html antigo e cria o link simbólico CORRETO
RUN rm -rf /var/www/html && ln -s /workspaces/financeiro2/public /var/www/html

# Garante permissões corretas para o socket do MySQL
RUN mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chmod 755 /var/run/mysqld

# Copia script de inicialização
COPY init.sql /tmp/init.sql

# Aplica o script com MySQL iniciado
RUN service mysql start && \
    sleep 5 && \
    mysql -u root < /tmp/init.sql
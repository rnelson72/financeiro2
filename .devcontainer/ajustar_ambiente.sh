#!/bin/bash
set -e

echo "Ajustando permissões da pasta pública..."
sudo chown -R $(whoami):$(whoami) /workspaces/sf/public
sudo chmod -R 755 /workspaces/sf/public

echo "Ajustando /var/www/html..."
sudo rm -rf /var/www/html
sudo ln -s /workspaces/sf/public /var/www/html

echo "Ajustando socket do MySQL..."
sudo mkdir -p /var/run/mysqld
sudo chown mysql:mysql /var/run/mysqld
sudo chown -R mysql:mysql /var/lib/mysql

echo "Restartando serviços..."
sudo service mysql restart
sudo service apache2 restart

echo "Aplicando dump do banco init.sql..."
cp /workspaces/sf/.devcontainer/init.sql /tmp/init.sql
sudo service mysql start
sleep 5
sudo mysql -u root < /tmp/init.sql > /tmp/mysql_import.log 2>&1

echo "Ambiente ajustado com sucesso!"
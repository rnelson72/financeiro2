ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'senha123';
FLUSH PRIVILEGES;
CREATE DATABASE IF NOT EXISTS meu_banco;

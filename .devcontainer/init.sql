ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'senha123';
FLUSH PRIVILEGES;
CREATE DATABASE IF NOT EXISTS meu_banco;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
USE meu_banco;

-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `banco`
--

DROP TABLE IF EXISTS `banco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banco` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `numero` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `agencia` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `conta` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `titular` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pix` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cartao`
--

DROP TABLE IF EXISTS `cartao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cartao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dia_vencimento` int DEFAULT NULL,
  `dia_fechamento` int DEFAULT NULL,
  `linha_credito` decimal(10,2) DEFAULT NULL,
  `banco_id` int DEFAULT NULL,
  `ativo` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `banco_id` (`banco_id`),
  CONSTRAINT `cartao_ibfk_1` FOREIGN KEY (`banco_id`) REFERENCES `banco` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conta` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ativo` tinyint DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cartao_id` int NOT NULL,
  `final_cartao_id` int DEFAULT NULL,
  `data` date NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `valor` decimal(12,2) NOT NULL,
  `parcelas` int DEFAULT '1',
  `parcela_atual` int DEFAULT '1',
  `categoria_id` int DEFAULT NULL,
  `fatura_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cartao_id` (`cartao_id`),
  KEY `final_cartao_id` (`final_cartao_id`),
  KEY `fatura_id` (`fatura_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`cartao_id`) REFERENCES `cartao` (`id`),
  CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`final_cartao_id`) REFERENCES `final_cartao` (`id`),
  CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`fatura_id`) REFERENCES `fatura` (`id`),
  CONSTRAINT `compras_ibfk_4` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2769 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `controle`
--

DROP TABLE IF EXISTS `controle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `controle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `grupo_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grupo_id` (`grupo_id`),
  CONSTRAINT `controle_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupo_controle` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fatura`
--

DROP TABLE IF EXISTS `fatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fatura` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cartao_id` int NOT NULL,
  `data_fechamento` date NOT NULL,
  `data_vencimento` date NOT NULL,
  `valor_total` decimal(12,2) DEFAULT NULL,
  `valor_pago` decimal(12,2) DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT 'aberta',
  `movimentacao_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cartao_id` (`cartao_id`),
  KEY `movimentacao_id` (`movimentacao_id`),
  CONSTRAINT `fatura_ibfk_1` FOREIGN KEY (`cartao_id`) REFERENCES `cartao` (`id`),
  CONSTRAINT `fatura_ibfk_2` FOREIGN KEY (`movimentacao_id`) REFERENCES `movimentacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `final_cartao`
--

DROP TABLE IF EXISTS `final_cartao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `final_cartao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `final` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cartao_id` int NOT NULL,
  `is_virtual` int DEFAULT '0',
  `titular` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ativo` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cartao_id` (`cartao_id`),
  CONSTRAINT `final_cartao_ibfk_1` FOREIGN KEY (`cartao_id`) REFERENCES `cartao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `grupo_controle`
--

DROP TABLE IF EXISTS `grupo_controle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_controle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `grupos_menu`
--

DROP TABLE IF EXISTS `grupos_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `icone` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ordem` int DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `lancamentos`
--

DROP TABLE IF EXISTS `lancamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lancamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `controle_id` int DEFAULT NULL,
  `data` date NOT NULL,
  `descricao` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `valor` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controle_id` (`controle_id`),
  CONSTRAINT `lancamentos_ibfk_1` FOREIGN KEY (`controle_id`) REFERENCES `controle` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `movimentacao`
--

DROP TABLE IF EXISTS `movimentacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimentacao` (
  `id` int NOT NULL,
  `data` date NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `valor` decimal(12,2) NOT NULL,
  `categoria_id` int NOT NULL,
  `banco_id` int NOT NULL,
  `codigo_pagamento` int DEFAULT NULL,
  `fatura_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `banco_id` (`banco_id`),
  CONSTRAINT `movimentacao_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  CONSTRAINT `movimentacao_ibfk_2` FOREIGN KEY (`banco_id`) REFERENCES `banco` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `permissoes`
--

DROP TABLE IF EXISTS `permissoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `transacao_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`transacao_id`),
  KEY `transacao_id` (`transacao_id`),
  CONSTRAINT `permissoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `permissoes_ibfk_2` FOREIGN KEY (`transacao_id`) REFERENCES `transacoes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissoes`
--

LOCK TABLES `permissoes` WRITE;
/*!40000 ALTER TABLE `permissoes` DISABLE KEYS */;
INSERT INTO `permissoes` VALUES (110,1,1),(111,1,2),(112,1,3),(113,1,4),(114,1,5),(115,1,6),(116,1,7),(117,1,8),(118,1,9),(119,1,10),(120,1,11),(121,1,12),(122,1,13),(123,1,14),(124,1,15),(125,1,16),(126,1,17),(127,1,18),(128,1,19),(129,1,20),(130,1,21),(131,1,22),(132,1,23),(133,1,24),(134,1,25),(135,1,26),(136,1,27),(137,1,28),(138,1,29),(139,1,30),(140,1,31),(164,1,32),(160,1,33),(161,1,34),(162,1,35),(163,1,36),(142,1,37),(143,1,38),(144,1,39),(145,1,40),(146,1,41),(147,1,42),(148,1,43),(149,1,44),(150,1,45),(151,1,46),(152,1,47),(153,1,48),(154,1,49),(155,1,50),(156,1,51),(157,1,52),(158,1,53),(159,1,54),(165,1,55),(166,1,56),(167,1,57),(168,1,58),(169,1,59),(170,1,60),(171,1,61),(172,1,62),(173,1,63),(174,1,64),(175,1,65),(176,1,68),(177,1,69),(178,1,70),(179,1,71),(180,1,72),(181,1,73),(182,1,74),(183,1,75),(184,1,76),(185,1,77),(186,1,78),(187,1,79),(141,1,80),(1,1,81),(2,1,82),(3,1,83),(188,1,84),(190,1,85),(107,2,37),(106,2,80),(108,3,1),(109,3,2);
/*!40000 ALTER TABLE `permissoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacoes`
--

DROP TABLE IF EXISTS `transacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nome` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `rota` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `componente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `grupo_id` int DEFAULT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `icone` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ordem` int DEFAULT '0',
  `visivel_no_menu` tinyint(1) DEFAULT '1',
  `ativo` tinyint(1) DEFAULT '1',
  `acao` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `grupo_id` (`grupo_id`),
  CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos_menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacoes`
--

LOCK TABLES `transacoes` WRITE;
/*!40000 ALTER TABLE `transacoes` DISABLE KEYS */;
INSERT INTO `transacoes` VALUES (1,'controle_listar','Listar Controles','controle','ControleController.php',1,'cadastro','',1,1,1,'listar'),(2,'controle_novo','Novo Controle','controle_novo','ControleController.php',1,'cadastro','',2,0,1,'novo'),(3,'controle_editar','Editar Controle','controle_editar','ControleController.php',1,'cadastro','',3,0,1,'editar'),(4,'controle_excluir','Excluir Controle','controle_excluir','ControleController.php',1,'cadastro','',4,0,1,'excluir'),(5,'controle_salvar','Salvar Controle','controle_salvar','ControleController.php',1,'cadastro','',5,0,1,'salvar'),(6,'controle_grupo_excluir','Excluir Grupo de Controle','grupo_excluir','ControleController.php',1,'cadastro','',6,0,1,'excluir_grupo'),(7,'controle_lancamentos','Listar Lançamentos','controle_lancamentos','ControleController.php',1,'cadastro','',7,0,1,'lancamentos'),(8,'controle_novo_lancamento','Novo Lançamento','controle_novo_lancamento','ControleController.php',1,'cadastro','',8,0,1,'novo_lancamento'),(9,'controle_editar_lancamento','Editar Lançamento','controle_editar_lancamento','ControleController.php',1,'cadastro','',9,0,1,'editar_lancamento'),(10,'controle_salvar_lancamento','Salvar Lançamento','controle_salvar_lancamento','ControleController.php',1,'cadastro','',10,0,1,'salvar_lancamento'),(11,'controle_excluir_lancamento','Excluir Lançamento','controle_excluir_lancamento','ControleController.php',1,'cadastro','',11,0,1,'excluir_lancamento'),(12,'banco_listar','Listar Bancos','banco','BancoController.php',1,'cadastro','bi bi-bank',20,1,1,'listar'),(13,'banco_novo','Novo Banco','banco_novo','BancoController.php',1,'cadastro','bi bi-bank',21,0,1,'novo'),(14,'banco_editar','Editar Banco','banco_editar','BancoController.php',1,'cadastro','bi bi-bank',22,0,1,'editar'),(15,'banco_excluir','Excluir Banco','banco_excluir','BancoController.php',1,'cadastro','bi bi-bank',23,0,1,'excluir'),(16,'banco_salvar','Salvar Banco','banco_salvar','BancoController.php',1,'cadastro','bi bi-bank',24,0,1,'salvar'),(17,'cartao_listar','Listar Cartões','cartao','CartaoController.php',1,'cadastro','bi bi-credit-card',30,1,1,'listar'),(18,'cartao_novo','Novo Cartão','cartao_novo','CartaoController.php',1,'cadastro','bi bi-credit-card',31,0,1,'novo'),(19,'cartao_editar','Editar Cartão','cartao_editar','CartaoController.php',1,'cadastro','bi bi-credit-card',32,0,1,'editar'),(20,'cartao_excluir','Excluir Cartão','cartao_excluir','CartaoController.php',1,'cadastro','bi bi-credit-card',33,0,1,'excluir'),(21,'cartao_salvar','Salvar Cartão','cartao_salvar','CartaoController.php',1,'cadastro','bi bi-credit-card',34,0,1,'salvar'),(22,'cartao_finais_listar','Listar Finais Cartão','final_cartao_listar','CartaoController.php',1,'cadastro','bi bi-credit-card',35,0,1,'final_listar'),(23,'cartao_finais_novo','Novo Final Cartão','final_cartao_novo','CartaoController.php',1,'cadastro','bi bi-credit-card',36,0,1,'final_novo'),(24,'cartao_finais_editar','Editar Final Cartão','final_cartao_editar','CartaoController.php',1,'cadastro','bi bi-credit-card',37,0,1,'final_editar'),(25,'cartao_finais_salvar','Salvar Final Cartão','final_cartao_salvar','CartaoController.php',1,'cadastro','bi bi-credit-card',38,0,1,'final_salvar'),(26,'cartao_finais_excluir','Excluir Final Cartão','final_cartao_excluir','CartaoController.php',1,'cadastro','bi bi-credit-card',39,0,1,'final_excluir'),(27,'categoria_listar','Listar Categorias','categoria','CategoriaController.php',1,'cadastro','bi bi-tags',40,1,1,'listar'),(28,'categoria_novo','Nova Categoria','categoria_novo','CategoriaController.php',1,'cadastro','bi bi-tags',41,0,1,'novo'),(29,'categoria_editar','Editar Categoria','categoria_editar','CategoriaController.php',1,'cadastro','bi bi-tags',42,0,1,'editar'),(30,'categoria_excluir','Excluir Categoria','categoria_excluir','CategoriaController.php',1,'cadastro','bi bi-tags',43,0,1,'excluir'),(31,'categoria_salvar','Salvar Categoria','categoria_salvar','CategoriaController.php',1,'cadastro','bi bi-tags',44,0,1,'salvar'),(32,'usuario_listar','Listar Usuários','usuario','UsuarioController.php',3,'admin','bi bi-people',90,1,1,'listar'),(33,'usuario_novo','Novo Usuário','usuario_novo','UsuarioController.php',3,'admin','bi bi-people',81,0,1,'novo'),(34,'usuario_editar','Editar Usuário','usuario_editar','UsuarioController.php',3,'admin','bi bi-people',82,0,1,'editar'),(35,'usuario_excluir','Excluir Usuário','usuario_excluir','UsuarioController.php',3,'admin','bi bi-people',83,0,1,'excluir'),(36,'usuario_salvar','Salvar Usuário','usuario_salvar','UsuarioController.php',3,'admin','bi bi-people',84,0,1,'salvar'),(37,'movimentacao_listar','Listar Movimentações','movimentacao','MovimentacaoController.php',2,'financeiro','bi bi-bar-chart',60,1,1,'listar'),(38,'movimentacao_novo','Nova Movimentação','movimentacao_nova','MovimentacaoController.php',2,'consulta','bi bi-bar-chart',61,0,1,'novo'),(39,'movimentacao_editar','Editar Movimentação','movimentacao_editar','MovimentacaoController.php',2,'consulta','bi bi-bar-chart',62,0,1,'editar'),(40,'movimentacao_excluir','Excluir Movimentação','movimentacao_excluir','MovimentacaoController.php',2,'consulta','bi bi-bar-chart',63,0,1,'excluir'),(41,'movimentacao_salvar','Salvar Movimentação','movimentacao_salvar','MovimentacaoController.php',2,'consulta','bi bi-bar-chart',64,0,1,'salvar'),(42,'compras_listar','Listar Compras','compras','CompraController.php',2,'consulta','bi bi-cart-plus',70,1,1,'listar'),(43,'compras_novo','Nova Compra','compras_nova','CompraController.php',2,'consulta','bi bi-cart-plus',71,0,1,'novo'),(44,'compras_editar','Editar Compra','compras_editar','CompraController.php',2,'consulta','bi bi-cart-plus',72,0,1,'editar'),(45,'compras_excluir','Excluir Compra','compras_excluir','CompraController.php',2,'consulta','bi bi-cart-plus',73,0,1,'excluir'),(46,'compras_salvar','Salvar Compra','compras_salvar','CompraController.php',2,'consulta','bi bi-cart-plus',74,0,1,'salvar'),(47,'faturas_listar','Listar Faturas','faturas','FaturaController.php',2,'financeiro','bi bi-receipt',80,1,1,'listar'),(48,'fatura_novo','Nova Fatura','fatura_nova','FaturaController.php',2,'consulta','bi bi-receipt',91,0,1,'novo'),(49,'fatura_editar','Editar Fatura','fatura_editar','FaturaController.php',2,'consulta','bi bi-receipt',92,0,1,'editar'),(50,'fatura_excluir','Excluir Fatura','fatura_excluir','FaturaController.php',2,'consulta','bi bi-receipt',93,0,1,'excluir'),(51,'fatura_salvar','Salvar Fatura','fatura_salvar','FaturaController.php',2,'consulta','bi bi-receipt',94,0,1,'salvar'),(52,'fatura_select_compras','Selecionar Compras na Fatura','fatura_select_compras','FaturaController.php',2,'consulta','bi bi-receipt',95,0,1,'select_compras'),(53,'fatura_fechar','Fechar Fatura','fatura_fechar','FaturaController.php',2,'consulta','bi bi-receipt',96,0,1,'fechar'),(54,'fatura_compras','Compras da Fatura','fatura_compras','FaturaController.php',2,'consulta','bi bi-receipt',97,0,1,'compras'),(55,'menu_listar','Listar Grupos de Menu','menu','MenuController.php',3,'admin','bi bi-list-ul',100,1,1,'listar'),(56,'menu_novo','Novo Grupo de Menu','menu_novo','MenuController.php',3,'consulta','bi bi-list-ul',101,0,1,'novo'),(57,'menu_editar','Editar Grupo de Menu','menu_editar','MenuController.php',3,'consulta','bi bi-list-ul',102,0,1,'editar'),(58,'menu_excluir','Excluir Grupo de Menu','menu_excluir','MenuController.php',3,'consulta','bi bi-list-ul',103,0,1,'excluir'),(59,'menu_salvar','Salvar Grupo de Menu','menu_salvar','MenuController.php',3,'consulta','bi bi-list-ul',104,0,1,'salvar'),(60,'transacao_listar','Listar Transações','transacao','TransacaoController.php',3,'admin','bi bi-flowchart',110,1,1,'listar'),(61,'transacao_novo','Nova Transação','transacao_novo','TransacaoController.php',3,'admin','bi bi-flowchart',111,0,1,'novo'),(62,'transacao_editar','Editar Transação','transacao_editar','TransacaoController.php',3,'admin','bi bi-flowchart',112,0,1,'editar'),(63,'transacao_excluir','Excluir Transação','transacao_excluir','TransacaoController.php',3,'admin','bi bi-flowchart',113,0,1,'excluir'),(64,'transacao_salvar','Salvar Transação','transacao_salvar','TransacaoController.php',3,'admin','bi bi-flowchart',114,0,1,'salvar'),(65,'permissoes_listar','Listar Permissões','permissao','PermissaoController.php',3,'admin','bi bi-shield-check',120,1,1,'listar'),(66,'permissoes_editar','Editar Permissões','permissoes_editar','PermissaoController.php',3,'admin','bi bi-shield-check',121,0,1,'editar'),(67,'permissoes_salvar','Salvar Permissões','permissoes_salvar','PermissaoController.php',3,'admin','bi bi-shield-check',122,0,1,'salvar'),(68,'login','Login','login','AuthController.php',3,'auth','',140,0,1,'login'),(69,'autenticar','Autenticar','autenticar','AuthController.php',3,'auth','',141,0,1,'autenticar'),(70,'logout','Logout','logout','AuthController.php',3,'auth','',142,0,1,'logout'),(71,'esqueci_senha','Esqueci Senha','esqueci_senha','AuthController.php',3,'auth','',143,0,1,'esqueci_senha'),(72,'esqueci_senha_post','Solicitar nova senha','esqueci_senha_post','AuthController.php',3,'auth','',144,0,1,'esqueci_senha_post'),(73,'redefinir_senha','Redefinir Senha','redefinir_senha','AuthController.php',3,'auth','',145,0,1,'redefinir_senha'),(74,'salvar_nova_senha','Salvar Nova Senha','salvar_nova_senha','AuthController.php',3,'auth','',146,0,1,'salvar_nova_senha'),(75,'migrar_cartao','Migrar Cartão','migcartao','MigrationController.php',3,'outros','',160,1,1,'migrateCartao'),(76,'migrar_categoria','Migrar Categoria','migrar_categoria','MigrationController.php',3,'consulta','',161,1,1,'migrateCategoria'),(77,'migrar_banco','Migrar Banco','migrar_banco','MigrationController.php',3,'consulta','',162,1,1,'migrateBanco'),(78,'migrar_controle','Migrar Controle','migrar_controle','MigrationController.php',3,'consulta','',163,1,1,'migrateControle'),(79,'migrar_movimentacao','Migrar Movimentação','migrar_movimentacao','MigrationController.php',3,'consulta','',164,1,1,'migrateMovimentacao'),(80,'melhor_cartao','Dashboard de cartões','dashboard','RelatorioController.php',4,'relatorio','',2,1,1,'melhorCartao'),(81,'relatorio_extrato','Extrato Simples','extrato','RelatorioController.php',4,'relatorio','',1,1,1,'extrato'),(82,'relatorio_top10_365d','TOP 10 Despesas','top10_365d','RelatorioController.php',4,'relatorio','',3,1,1,'top10_365d'),(83,'relatorio_evolucao','Evolução por Categoria','evolucao','RelatorioController.php',4,'relatorio','',4,1,1,'evolucao'),(84,'relatorio_sintetico','Sintético','sintetico','RelatorioController.php',4,'relatorio','',5,1,1,'sintetico'),(85,'relatorio_analitico','Relatório Analítico','analitico','RelatorioController.php',4,'relatorio','',6,1,1,'analitico');
/*!40000 ALTER TABLE `transacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `senha_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Ricardo Nelson','sup.ricardo@gmail.com','$2y$10$9arbOi/Ds2SLTA4zoygyiu3x8q32w8n2Ozq5wT2Catlmb8h7my8HO','2025-03-25 14:09:40'),(2,'Izabel Cristina','makitubbb@gmail.com','$2y$10$91lW/5t2d8Xj8OXDjJeXge/m84mwU6Kngdb1kIB.9O/HERuG0gVgK','2025-03-28 02:08:21'),(3,'Pablinny Thauanny','pablinnythauanny97@gmail.com','scrypt:32768:8:1$oOfEZsgGbv9GVNyd$b98f2581c8b9200ccc90dcf5a97d36cb1eb3f773b2024ed6adc8f03f36cdb469205a5bfc459518e0dec812bcd4dea73140088052ae2ab3d8fb0e4bed08603320','2025-03-28 02:26:59'),(4,'Teste do Manoel','ricardongoncalves@yahoo.com.br','$2y$10$/F72QHrRsyg6uxV.EIvZWOXDycALQT9OWxQ5rTtNIyRYMiy94GbVW','2025-04-26 16:37:31');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_controle`
--

DROP TABLE IF EXISTS `vw_controle`;
/*!50001 DROP VIEW IF EXISTS `vw_controle`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_controle` AS SELECT 
 1 AS `id`,
 1 AS `descricao`,
 1 AS `ativo`,
 1 AS `grupo_id`,
 1 AS `grupo`,
 1 AS `saldo`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_receitas_despesas`
--

DROP TABLE IF EXISTS `vw_receitas_despesas`;
/*!50001 DROP VIEW IF EXISTS `vw_receitas_despesas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_receitas_despesas` AS SELECT 
 1 AS `id`,
 1 AS `data`,
 1 AS `descr`,
 1 AS `valor`,
 1 AS `categoria_id`,
 1 AS `tipo`,
 1 AS `Pagamento`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'meu_banco'
--
/*!50003 DROP FUNCTION IF EXISTS `total_conta_mensal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `total_conta_mensal`(
    `categoria_id` INT,
    `mes_ref` VARCHAR(7)
) RETURNS decimal(12,2)
    READS SQL DATA
BEGIN
    DECLARE tipo_conta VARCHAR(10);
    DECLARE prefixo VARCHAR(10);
    DECLARE total DECIMAL(12, 2) DEFAULT 0;

    
    SELECT tipo, conta INTO tipo_conta, prefixo
    FROM   categoria
    WHERE id = categoria_id;

    IF tipo_conta = 'SUBTOTAL' THEN
        SELECT SUM(sub.total) INTO total
        FROM categoria c
        LEFT JOIN (
              SELECT m.categoria_id, SUM(m.valor) as total
              FROM   vw_receitas_despesas m
              WHERE DATE_FORMAT(m.data, '%Y-%m') = mes_ref
              GROUP BY m.categoria_id
                ) sub ON sub.categoria_id = c.id
        WHERE c.conta LIKE CONCAT(prefixo, '%') AND c.conta != prefixo;
    ELSE
        SELECT COALESCE(SUM(m.valor), 0) INTO total
        FROM vw_receitas_despesas m
        WHERE m.categoria_id = categoria_id
          AND DATE_FORMAT(m.data, '%Y-%m') = mes_ref;
    END IF;

    RETURN total;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `vw_controle`
--

/*!50001 DROP VIEW IF EXISTS `vw_controle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_controle` AS select `c`.`id` AS `id`,`c`.`descricao` AS `descricao`,`c`.`ativo` AS `ativo`,`c`.`grupo_id` AS `grupo_id`,`g`.`descricao` AS `grupo`,coalesce(sum(`l`.`valor`),0) AS `saldo` from ((`controle` `c` left join `lancamentos` `l` on((`l`.`controle_id` = `c`.`id`))) left join `grupo_controle` `g` on((`g`.`id` = `c`.`grupo_id`))) group by `c`.`id`,`c`.`descricao`,`c`.`ativo`,`c`.`grupo_id`,`g`.`descricao` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_receitas_despesas`
--

/*!50001 DROP VIEW IF EXISTS `vw_receitas_despesas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_receitas_despesas` AS select `m`.`id` AS `id`,`m`.`data` AS `data`,`m`.`descricao` AS `descr`,`m`.`valor` AS `valor`,`m`.`categoria_id` AS `categoria_id`,'M' AS `tipo`,`b`.`descricao` AS `Pagamento` from (`movimentacao` `m` join `banco` `b` on((`b`.`id` = `m`.`banco_id`))) where (`m`.`fatura_id` is null) union all select `co`.`id` AS `id`,`f`.`data_vencimento` AS `data`,if((`co`.`parcelas` = 1),`co`.`descricao`,concat(`co`.`descricao`,' (',`co`.`parcela_atual`,'/',`co`.`parcelas`,')')) AS `descr`,`co`.`valor` AS `valor`,`co`.`categoria_id` AS `categoria_id`,'C' AS `tipo`,`ca`.`descricao` AS `Pagamento` from ((`compras` `co` join `cartao` `ca` on((`ca`.`id` = `co`.`cartao_id`))) join `fatura` `f` on((`f`.`id` = `co`.`fatura_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-03 20:45:50

-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.6.4-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura para tabela financas.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `log` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `ip` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.bancos
CREATE TABLE IF NOT EXISTS `bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `banco` int(11) DEFAULT NULL,
  `agencia` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `conta` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `digito` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `observacoes` text COLLATE armscii8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.cartoes
CREATE TABLE IF NOT EXISTS `cartoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nome` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `emissor` varchar(15) COLLATE armscii8_bin DEFAULT NULL,
  `ultimos_digitos` varchar(10) COLLATE armscii8_bin DEFAULT NULL,
  `bandeira` varchar(10) COLLATE armscii8_bin DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.categorias_ganhos
CREATE TABLE IF NOT EXISTS `categorias_ganhos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nome` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `cor_hex` varchar(50) COLLATE armscii8_bin DEFAULT '#eb4034',
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.categorias_gastos
CREATE TABLE IF NOT EXISTS `categorias_gastos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nome` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `cor_hex` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '#eb4034',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.ganhos
CREATE TABLE IF NOT EXISTS `ganhos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `data` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `banco_id` int(11) DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `recorrente` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.gastos
CREATE TABLE IF NOT EXISTS `gastos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `data` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `banco_id` int(11) DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `recorrente` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela financas.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `password` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `last_ip` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `last_time` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `reg_ip` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `reg_time` int(11) DEFAULT NULL,
  `currency` enum('BRL','USD','EUR') CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `discord_log_webhook_url` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `discord_log_webhook_enabled` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin COMMENT='Users table';

-- Exportação de dados foi desmarcado.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

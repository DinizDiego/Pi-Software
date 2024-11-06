-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: axo
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cargos`
--

DROP TABLE IF EXISTS `cargos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cargos` (
  `id_cargo` int NOT NULL AUTO_INCREMENT,
  `cargo` varchar(200) NOT NULL,
  PRIMARY KEY (`id_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos`
--

LOCK TABLES `cargos` WRITE;
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
INSERT INTO `cargos` VALUES (1,'Docente'),(2,'Funcionário'),(3,'Administrador');
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docentes`
--

DROP TABLE IF EXISTS `docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `docentes` (
  `id_docente` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` char(13) NOT NULL,
  `cep` char(8) NOT NULL,
  `turno` tinyint NOT NULL,
  `codigo_barras` varchar(100) NOT NULL,
  `cpf` char(11) NOT NULL,
  PRIMARY KEY (`id_docente`),
  UNIQUE KEY `codigo_barras_UNIQUE` (`codigo_barras`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docentes`
--

LOCK TABLES `docentes` WRITE;
/*!40000 ALTER TABLE `docentes` DISABLE KEYS */;
INSERT INTO `docentes` VALUES (1,'Diego','2005-04-16','123','09241-18',0,'12323123','140.543.238'),(2,'DiegoD','2005-04-16','123','09241-18',0,'12323124','140.543.238');
/*!40000 ALTER TABLE `docentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entradas_saidas`
--

DROP TABLE IF EXISTS `entradas_saidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entradas_saidas` (
  `id_entrada_saida` int NOT NULL AUTO_INCREMENT,
  `id_docente` int NOT NULL,
  `id_docente2` int DEFAULT NULL,
  `id_kit` int NOT NULL,
  `data_saida` timestamp NOT NULL,
  `data_entrada` timestamp NULL DEFAULT NULL,
  `observacao_saida` text,
  `observacao_entrada` text,
  PRIMARY KEY (`id_entrada_saida`),
  KEY `fk_entradas_saidas_usuarios_idx` (`id_docente`),
  KEY `fk_entradas_saidas_kits_idx` (`id_kit`),
  CONSTRAINT `fk_entradas_saidas_kits` FOREIGN KEY (`id_kit`) REFERENCES `kits` (`id_kit`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entradas_saidas`
--

LOCK TABLES `entradas_saidas` WRITE;
/*!40000 ALTER TABLE `entradas_saidas` DISABLE KEYS */;
INSERT INTO `entradas_saidas` VALUES (1,1,2,2,'2024-10-28 03:34:09','2024-10-28 03:34:32','Tudo Completo','Faltando 1 caneta'),(2,1,NULL,2,'2024-10-28 03:34:58',NULL,'Tudo Completo',NULL);
/*!40000 ALTER TABLE `entradas_saidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kits`
--

DROP TABLE IF EXISTS `kits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kits` (
  `id_kit` int NOT NULL AUTO_INCREMENT,
  `n_sala` char(10) NOT NULL,
  `descricao` text NOT NULL,
  `situacao` tinyint NOT NULL,
  `codigo_barras_kit` varchar(225) NOT NULL,
  PRIMARY KEY (`id_kit`),
  UNIQUE KEY `n_sala_UNIQUE` (`n_sala`),
  UNIQUE KEY `rfid_UNIQUE` (`codigo_barras_kit`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kits`
--

LOCK TABLES `kits` WRITE;
/*!40000 ALTER TABLE `kits` DISABLE KEYS */;
INSERT INTO `kits` VALUES (1,'1','Controle do Data Show e cartão da sala',1,'1946753214860'),(2,'2','Controle do Data Show e cartão da sala e chave dos armários',2,'1946753214861'),(3,'3','Controle do Data Show e cartão da sala',1,'1946753214862'),(4,'4','Controle do Data Show e cartão da sala',1,'1946753214863'),(5,'5','Controle do Data Show e cartão da sala',1,'1946753214864'),(7,'6','40990672',1,'40990672');
/*!40000 ALTER TABLE `kits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_cargo` int NOT NULL,
  `nome` varchar(200) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` char(13) NOT NULL,
  `cep` char(8) NOT NULL,
  `turno` tinyint DEFAULT NULL,
  `codigo_barras` varchar(100) DEFAULT NULL,
  `cpf` char(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `senha` varchar(45) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuarios_cargos_idx` (`id_cargo`),
  CONSTRAINT `fk_usuarios_cargos` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,1,'fsda','2024-09-05','fw','ad@a',0,'dad','as','DinizDiego','12IbR.gJ8wcpc'),(2,1,'Diego','2005-04-16','123','09241-18',NULL,NULL,'140.543.238','Diego','12IbR.gJ8wcpc');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-28 20:54:24

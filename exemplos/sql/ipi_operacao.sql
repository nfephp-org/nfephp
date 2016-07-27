/*CONTRIBUIÇÃO DE ANDREUS TIMM*/
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ipi_operacao` (
  `id_ipi_operacao` INT(16) NOT NULL AUTO_INCREMENT ,
  `nome_ipi_operacao` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`id_ipi_operacao`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

TRUNCATE TABLE `ipi_operacao`;

INSERT INTO `ipi_operacao` (id_ipi_operacao, nome_ipi_operacao) VALUES (1, 'Operação normal e outros');
INSERT INTO `ipi_operacao` (id_ipi_operacao, nome_ipi_operacao) VALUES (2, 'Operação isenta');
INSERT INTO `ipi_operacao` (id_ipi_operacao, nome_ipi_operacao) VALUES (3, 'Operação imune');
INSERT INTO `ipi_operacao` (id_ipi_operacao, nome_ipi_operacao) VALUES (4, 'Operação com suspensão');
INSERT INTO `ipi_operacao` (id_ipi_operacao, nome_ipi_operacao) VALUES (5, 'Operação com redução');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

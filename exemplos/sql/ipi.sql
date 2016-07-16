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
CREATE TABLE IF NOT EXISTS `ipi` (
  `id_ipi` INT(16) NOT NULL AUTO_INCREMENT ,
  `codigo_ipi` VARCHAR(16) NOT NULL ,
  `desc_ipi` TEXT NOT NULL ,
  PRIMARY KEY (`id_ipi`) )
ENGINE = InnoDB;

TRUNCATE TABLE `ipi`;

INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('00', '00: Entrada com recuperação de crédito');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('01', '01: Entrada tributada com alíquota zero');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('02', '02: Entrada isenta');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('03', '03: Entrada não-tributada');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('04', '04: Entrada imune');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('05', '05: Entrada com suspensão');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('49', '49: Outras entradas');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('50', '50: Saída tributada');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('51', '51: Saída tributada com alíquota zero');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('52', '52: Saída isenta');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('53', '53: Saída não-tributada');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('54', '54: Saída imune');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('55', '55: Saída com suspensão');
INSERT INTO `ipi` (codigo_ipi, desc_ipi) VALUES ('99', '99: Outras saídas');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

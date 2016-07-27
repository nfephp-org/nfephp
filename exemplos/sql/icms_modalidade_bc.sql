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
CREATE TABLE IF NOT EXISTS `icms_modalidade_bc` (
  `id_icms_modalidade_bc` int(16) NOT NULL AUTO_INCREMENT,
  `codigo_icms_modalidade_bc` varchar(16) NOT NULL,
  `desc_icms_modalidade_bc` varchar(128) NOT NULL,
  PRIMARY KEY (`id_icms_modalidade_bc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `icms_modalidade_bc`;

INSERT INTO `icms_modalidade_bc` (codigo_icms_modalidade_bc, desc_icms_modalidade_bc) VALUES ('0', 'Margem valor adicionado');
INSERT INTO `icms_modalidade_bc` (codigo_icms_modalidade_bc, desc_icms_modalidade_bc) VALUES ('1', 'Pauta (valor)');
INSERT INTO `icms_modalidade_bc` (codigo_icms_modalidade_bc, desc_icms_modalidade_bc) VALUES ('2', 'Preço tabelado máx. (valor)');
INSERT INTO `icms_modalidade_bc` (codigo_icms_modalidade_bc, desc_icms_modalidade_bc) VALUES ('3', 'Valor da operação');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

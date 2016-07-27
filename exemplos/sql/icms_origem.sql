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
CREATE TABLE IF NOT EXISTS `icms_origem` (
  `id_icms_origem` int(16) NOT NULL AUTO_INCREMENT,
  `codigo_icms_origem` varchar(16) NOT NULL,
  `desc_icms_origem` varchar(128) NOT NULL,
  PRIMARY KEY (`id_icms_origem`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `icms_origem`;

INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('0', 'Nacional, exceto as indicadas nos códigos de 3 a 5');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('1', 'Estrangeira - Importação direta, exceto a indicada no código 6');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('2', 'Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('3', 'Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40%');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('4', 'Nacional, produção em conformidade com processos básicos que tratam as legisl. dos Ajustes');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('5', 'Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('6', 'Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX');
INSERT INTO `icms_origem` (codigo_icms_origem, desc_icms_origem) VALUES ('7', 'Estrangeira - Adquirida mercado interno, sem similar nacional, constante em lista da CAMEX');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

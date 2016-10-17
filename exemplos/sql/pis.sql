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
CREATE TABLE IF NOT EXISTS `pis` (
  `id_pis` INT(16) NOT NULL AUTO_INCREMENT ,
  `codigo_pis` VARCHAR(16) NOT NULL ,
  `desc_pis` TEXT NOT NULL ,
  PRIMARY KEY (`id_pis`) )
ENGINE = InnoDB;

TRUNCATE TABLE `pis`;

INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('01', '01: Operação tributável (BC = Operação alíq. normal (cumul./não cumul.)');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('02', '02: Operação tributável (BC = valor da operação (alíquota diferenciada)');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('03', '03: Operação tributável (BC = quant. x alíq. por unidade de produto)');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('04', '04: Operação tributável (tributação monofásica, alíquota zero)');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('06', '06: Operação tributável (alíquota zero)');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('07', '07: Operação isenta da contribuição');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('08', '08: Operação sem incidência da contribuição');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('09', '09: Operação com suspensão da contribuição');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('49', '49: Outras Operações de Saída');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('50', '50: Direito a Crédito. Vinculada Exclusivamente a Receita Tributada no Mercado Interno');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('51', '51: Direito a Crédito. Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('52', '52: Direito a Crédito. Vinculada Exclusivamente a Receita de Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('53', '53: Direito a Crédito. Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('54', '54: Direito a Crédito. Vinculada a Receitas Tributadas no Mercado Interno e de Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('55', '55: Direito a Crédito. Vinculada a Receitas Não-Trib. no Mercado Interno e de Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('56', '56: Direito a Crédito. Vinculada a Rec. Trib. e Não-Trib. Mercado Interno e Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('60', '60: Créd. Presumido. Aquisição Vinc. Exclusivamente a Receita Tributada no Mercado Interno');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('61', '61: Créd. Presumido. Aquisição Vinc. Exclusivamente a Rec. Não-Trib. no Mercado Interno');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('62', '62: Créd. Presumido. Aquisição Vinc. Exclusivamente a Receita de Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('63', '63: Créd. Presumido. Aquisição Vinc. a Rec. Trib. e Não-Trib. no Mercado Interno');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('64', '64: Créd. Presumido. Aquisição Vinc. a Rec. Trib. no Mercado Interno e de Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('65', '65: Créd. Presumido. Aquisição Vinc. a Rec. Não-Trib. Mercado Interno e Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('66', '66: Créd. Presumido. Aquisição Vinc. a Rec. Trib. e Não-Trib. Mercado Interno e Exportação');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('67', '67: Crédito Presumido - Outras Operações');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('70', '70: Operação de Aquisição sem Direito a Crédito');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('71', '71: Operação de Aquisição com Isenção');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('72', '72: Operação de Aquisição com Suspensão');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('73', '73: Operação de Aquisição a Alíquota Zero');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('74', '74: Operação de Aquisição sem Incidência da Contribuição');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('75', '75: Operação de Aquisição por Substituição Tributária');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('98', '98: Outras Operações de Entrada');
INSERT INTO `pis` (codigo_pis, desc_pis) VALUES ('99', '99: Outras operações');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

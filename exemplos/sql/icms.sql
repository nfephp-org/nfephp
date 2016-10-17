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

CREATE TABLE IF NOT EXISTS `icms` (
  `id_icms` INT(16) NOT NULL AUTO_INCREMENT ,
  `codigo_icms` VARCHAR(16) NOT NULL ,
  `desc_icms` TEXT NOT NULL ,
  PRIMARY KEY (`id_icms`) )
ENGINE = InnoDB;

TRUNCATE TABLE `icms`;

INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (1, '00', '00: Tributada integralmente');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (2, '10', '10: Tributada com cobr. por subst. trib.');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (3, '20', '20: Com redução de base de cálculo');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (4, '30', '30: Isenta ou não trib com cobr por subst trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (5, '40', '40: Isenta');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (6, '41', '41: Não tributada');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (7, '50', '50: Suspesão');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (8, '51', '51: Diferimento');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (9, '60', '60: ICMS cobrado anteriormente por subst trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (10, '70', '70: Redução de Base Calc e cobr ICMS por subst trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (11, '90', '90: Outros');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (12, '10Part', 'Partilha 10: Entre UF origem e destino ou definida na legislação com Subst Trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (13, '90Part', 'Partilha 90: Entre UF origem e destino ou definida na legislação - outros');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (14, '41ST', 'Repasse 41: ICMS ST retido em operações interestaduais com repasses do Subst Trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (15, '101', 'Simples Nacional: 101: Com permissão de crédito');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (16, '102', 'Simples Nacional: 102: Sem permissão de crédito');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (17, '103', 'Simples Nacional: 103: Isenção do ICMS para faixa de receita bruta');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (18, '201', 'Simples Nacional: 201: Com permissão de crédito, com cobr ICMS por Subst Trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (19, '202', 'Simples Nacional: 202: Sem permissão de crédito, com cobr ICMS por Subst Trib');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (20, '203', 'Simples Nacional: 203: Isenção ICMS p/ faixa de receita bruta e cobr do ICMS por ST');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (21, '300', 'Simples Nacional: 300: Imune');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (22, '400', 'Simples Nacional: 400: Não tributada');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (23, '500', 'Simples Nacional: 500: ICMS cobrado antes por subst trib ou antecipação');
INSERT INTO `icms` (`id_icms`, `codigo_icms`, `desc_icms`) VALUES (24, '900', 'Simples Nacional: 900: Outros');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

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
CREATE  TABLE IF NOT EXISTS `estado` (
  `id_estado` INT(16) NOT NULL AUTO_INCREMENT ,
  `nome_estado` VARCHAR(64) NOT NULL ,
  `uf_estado` VARCHAR(2) NOT NULL ,
  `codigo_estado` VARCHAR(2) NOT NULL ,
  PRIMARY KEY (`id_estado`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

TRUNCATE TABLE `estado`;

INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (1, 'Acre', 'AC', '12');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (2, 'Alagoas', 'AL', '27');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (3, 'Amapá', 'AP', '16');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (4, 'Amazonas', 'AM', '13');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (5, 'Bahia', 'BA', '29');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (6, 'Ceará', 'Ce', '23');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (7, 'Distrito Federal', 'DF', '53');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (8, 'Espírito Santo', 'ES', '32');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (9, 'Goiás', 'GO', '52');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (10, 'Maranhão', 'MA', '21');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (11, 'Mato Grosso do Sul', 'MS', '50');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (12, 'Mato Grosso', 'MT', '51');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (13, 'Minas Gerais', 'MS', '31');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (14, 'Paraná', 'PR', '41');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (15, 'Paraíba', 'PB', '25');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (16, 'Pará', 'PA', '15');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (17, 'Pernambuco', 'PE', '26');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (18, 'Piauí', 'PI', '22');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (19, 'Rio de Janeiro', 'RJ', '33');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (20, 'Rio Grande do Norte', 'RN', '24');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (21, 'Rio Grande do Sul', 'RS', '43');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (22, 'Rondônia', 'RO', '11');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (23, 'Roraima', 'RR', '14');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (24, 'Santa Catarina', 'SC', '42');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (25, 'Sergipe', 'SE', '28');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (26, 'São Paulo', 'SP', '35');
INSERT INTO `estado` (id_estado, nome_estado, uf_estado, codigo_estado) VALUES (27, 'Tocantins', 'TO', '17');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

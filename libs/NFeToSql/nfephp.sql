# --------------------------------------------------------
# Database:             nfephp
# Server version:       5.1.56
# Server OS:            unknown-linux-gnu
# Target-Compatibility: MySQL 5.1
# --------------------------------------------------------

/*!40100 SET CHARACTER SET latin1*/;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0*/;


#
# Database structure for database 'nfephp'
#

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `nfephp` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `nfephp`;


#
# Table structure for table 'COFINS'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `COFINS` (
  `COFINS_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `CST` char(2) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `pCOFINS` double DEFAULT NULL,
  `qBCProd` double DEFAULT NULL,
  `vAliqProd` double DEFAULT NULL,
  `vCOFINS` double DEFAULT NULL,
  PRIMARY KEY (`COFINS_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `COFINS_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'COFINS'
#

# (No data found.)



#
# Table structure for table 'COFINSST'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `COFINSST` (
  `COFINSST_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `pCOFINS` double DEFAULT NULL,
  `qBCProd` double DEFAULT NULL,
  `vAliqProd` double DEFAULT NULL,
  `vCOFINS` double DEFAULT NULL,
  PRIMARY KEY (`COFINSST_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `COFINSST_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'COFINSST'
#

# (No data found.)



#
# Table structure for table 'DI'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `DI` (
  `DI_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `nDI` varchar(10) DEFAULT NULL,
  `dDi` date DEFAULT NULL,
  `xLocDesemb` varchar(60) DEFAULT NULL,
  `UFDesemb` char(2) DEFAULT NULL,
  `dDesemb` date DEFAULT NULL,
  `cExportador` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`DI_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `DI_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'DI'
#

# (No data found.)



#
# Table structure for table 'ICMS'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `ICMS` (
  `ICMS_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `orig` char(1) DEFAULT NULL,
  `CST` char(2) DEFAULT NULL,
  `modBC` char(1) DEFAULT NULL,
  `pRedBC` double DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `pICMS` double DEFAULT NULL,
  `vICMS` double DEFAULT NULL,
  `modBCST` char(1) DEFAULT NULL,
  `pMVAST` double DEFAULT NULL,
  `pRedBCST` double DEFAULT NULL,
  `vBCST` double DEFAULT NULL,
  `pICMSST` double DEFAULT NULL,
  `vICMSST` double DEFAULT NULL,
  `motDesICMS` tinyint(2) DEFAULT NULL,
  `vBCSTRet` double DEFAULT NULL,
  `vICMSSTRet` double DEFAULT NULL,
  `pBCOp` double DEFAULT NULL,
  `UFST` char(2) DEFAULT NULL,
  `vBCSTDest` double DEFAULT NULL,
  `vICMSSTDest` double DEFAULT NULL,
  `CSOSN` int(3) DEFAULT NULL,
  `pCredSN` double DEFAULT NULL,
  `vCredICMSSN` double DEFAULT NULL,
  PRIMARY KEY (`ICMS_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `ICMS_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'ICMS'
#

# (No data found.)



#
# Table structure for table 'ICMSTot'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `ICMSTot` (
  `ICMSTot_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `vICMS` double DEFAULT NULL,
  `vBCST` double DEFAULT NULL,
  `vST` double DEFAULT NULL,
  `vProd` double DEFAULT NULL,
  `vFrete` double DEFAULT NULL,
  `vSeg` double DEFAULT NULL,
  `vDesc` double DEFAULT NULL,
  `vII` double DEFAULT NULL,
  `vIPI` double DEFAULT NULL,
  `vPIS` double DEFAULT NULL,
  `vCOFINS` double DEFAULT NULL,
  `vOutro` double DEFAULT NULL,
  `vNF` double DEFAULT NULL,
  PRIMARY KEY (`ICMSTot_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `ICMSTot_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'ICMSTot'
#

# (No data found.)



#
# Table structure for table 'II'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `II` (
  `II_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `vDespAdu` double DEFAULT NULL,
  `vII` double DEFAULT NULL,
  `vIOF` double DEFAULT NULL,
  PRIMARY KEY (`II_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `II_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'II'
#

# (No data found.)



#
# Table structure for table 'IPI'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `IPI` (
  `IPI_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `clEnq` char(5) DEFAULT NULL,
  `CNPJProd` char(14) DEFAULT NULL,
  `cSelo` varchar(60) DEFAULT NULL,
  `qSelo` double DEFAULT NULL,
  `cEnq` char(3) DEFAULT NULL,
  `CST` char(2) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `qUnid` double DEFAULT NULL,
  `vUnid` double DEFAULT NULL,
  `pIPI` double DEFAULT NULL,
  `vIPI` double DEFAULT NULL,
  PRIMARY KEY (`IPI_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `IPI_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'IPI'
#

# (No data found.)



#
# Table structure for table 'ISSQN'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `ISSQN` (
  `ISSQN_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `vAliq` double DEFAULT NULL,
  `vISSQN` double DEFAULT NULL,
  `cMunFG` char(7) DEFAULT NULL,
  `cListServ` varchar(4) DEFAULT NULL,
  `cSitTrib` char(1) DEFAULT NULL,
  PRIMARY KEY (`ISSQN_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `ISSQN_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'ISSQN'
#

# (No data found.)



#
# Table structure for table 'ISSQNtot'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `ISSQNtot` (
  `ISSQNtot_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `vServ` double DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `vISS` double DEFAULT NULL,
  `vPIS` double DEFAULT NULL,
  `vCOFINS` double DEFAULT NULL,
  PRIMARY KEY (`ISSQNtot_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `ISS_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'ISSQNtot'
#

# (No data found.)



#
# Table structure for table 'NFe'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `NFe` (
  `NFe_id` int(11) NOT NULL AUTO_INCREMENT,
  `situacao_id` int(3) DEFAULT NULL,
  `versao` varchar(4) DEFAULT NULL,
  `Id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`NFe_id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `situacao_id` (`situacao_id`),
  CONSTRAINT `NFe_ibfk_1` FOREIGN KEY (`situacao_id`) REFERENCES `situacao` (`situacao_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'NFe'
#

# (No data found.)



#
# Table structure for table 'PIS'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `PIS` (
  `PIS_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `CST` char(2) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `pPIS` double DEFAULT NULL,
  `vPIS` double DEFAULT NULL,
  `qBCProd` double DEFAULT NULL,
  `vAliqProd` double DEFAULT NULL,
  PRIMARY KEY (`PIS_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `PIS_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'PIS'
#

# (No data found.)



#
# Table structure for table 'PISST'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `PISST` (
  `PISST_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `pPIS` double DEFAULT NULL,
  `qBCProd` double DEFAULT NULL,
  `vAliqProd` double DEFAULT NULL,
  `vPIS` double DEFAULT NULL,
  PRIMARY KEY (`PISST_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `PISST_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'PISST'
#

# (No data found.)



#
# Table structure for table 'adi'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `adi` (
  `adi_id` int(11) NOT NULL AUTO_INCREMENT,
  `DI_id` int(11) DEFAULT NULL,
  `nAdicao` int(11) DEFAULT NULL,
  `nSeqAdic` int(11) DEFAULT NULL,
  `cFabricante` varchar(60) DEFAULT NULL,
  `vDescDI` double DEFAULT NULL,
  PRIMARY KEY (`adi_id`),
  KEY `DI_id` (`DI_id`),
  CONSTRAINT `adi_ibfk_1` FOREIGN KEY (`DI_id`) REFERENCES `DI` (`DI_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'adi'
#

# (No data found.)



#
# Table structure for table 'arma'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `arma` (
  `arma_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `tpArma` int(11) DEFAULT NULL,
  `nSerie` varchar(9) DEFAULT NULL,
  `nCano` varchar(9) DEFAULT NULL,
  `descr` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`arma_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `arma_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'arma'
#

# (No data found.)



#
# Table structure for table 'avulsa'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `avulsa` (
  `avulsa_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `xOrgao` varchar(60) DEFAULT NULL,
  `matr` varchar(60) DEFAULT NULL,
  `xAgente` varchar(60) DEFAULT NULL,
  `fone` varchar(14) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  `nDAR` varchar(60) DEFAULT NULL,
  `dEmi` date DEFAULT NULL,
  `vDAR` varchar(15) DEFAULT NULL,
  `repEmi` varchar(60) DEFAULT NULL,
  `dPag` date DEFAULT NULL,
  PRIMARY KEY (`avulsa_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `avulsa_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'avulsa'
#

# (No data found.)



#
# Table structure for table 'comb'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `comb` (
  `comb_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `cProdANP` varchar(9) DEFAULT NULL,
  `CODIF` varchar(21) DEFAULT NULL,
  `qTemp` double DEFAULT NULL,
  `qBCprod` double DEFAULT NULL,
  `vAliqProd` double DEFAULT NULL,
  `vCIDE` double DEFAULT NULL,
  `vBCICMS` double DEFAULT NULL,
  `vICMS` double DEFAULT NULL,
  `vBCICMSST` double DEFAULT NULL,
  `vICMSST` double DEFAULT NULL,
  `vBCICMSSTDest` double DEFAULT NULL,
  `vICMSSTDest` double DEFAULT NULL,
  `vBCICMSSTCons` double DEFAULT NULL,
  `vICMSSTCons` double DEFAULT NULL,
  `UFcons` char(2) DEFAULT NULL,
  PRIMARY KEY (`comb_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `comb_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'comb'
#

# (No data found.)



#
# Table structure for table 'compra'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `compra` (
  `compra_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `xNEmp` varchar(17) DEFAULT NULL,
  `xPed` varchar(60) DEFAULT NULL,
  `xCont` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`compra_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'compra'
#

# (No data found.)



#
# Table structure for table 'dest'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `dest` (
  `dest_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `CPF` char(11) DEFAULT NULL,
  `xNome` varchar(60) DEFAULT NULL,
  `IE` varchar(14) DEFAULT NULL,
  `ISUF` char(9) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`dest_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `dest_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'dest'
#

# (No data found.)



#
# Table structure for table 'det'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `det` (
  `det_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `nItem` tinyint(3) unsigned DEFAULT NULL,
  `infAdProd` text,
  PRIMARY KEY (`det_id`),
  KEY `det_id` (`det_id`),
  KEY `det_ibfk_1` (`NFe_id`),
  CONSTRAINT `det_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'det'
#

# (No data found.)



#
# Table structure for table 'dup'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `dup` (
  `dup_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `nDup` varchar(60) DEFAULT NULL,
  `dVenc` date DEFAULT NULL,
  `vDup` double DEFAULT NULL,
  PRIMARY KEY (`dup_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `dup_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'dup'
#

# (No data found.)



#
# Table structure for table 'emit'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `emit` (
  `emit_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `CPF` char(11) DEFAULT NULL,
  `xNome` varchar(60) DEFAULT NULL,
  `xFant` varchar(60) DEFAULT NULL,
  `IE` varchar(14) DEFAULT NULL,
  `IEST` varchar(14) DEFAULT NULL,
  `IM` varchar(15) DEFAULT NULL,
  `CNAE` char(7) DEFAULT NULL,
  `CRT` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`emit_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `emit_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'emit'
#

# (No data found.)



#
# Table structure for table 'enderDest'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `enderDest` (
  `enderDest_id` int(11) NOT NULL AUTO_INCREMENT,
  `dest_id` int(11) DEFAULT NULL,
  `xLgr` varchar(60) DEFAULT NULL,
  `nro` varchar(60) DEFAULT NULL,
  `xCpl` varchar(60) DEFAULT NULL,
  `xBairro` varchar(60) DEFAULT NULL,
  `cMun` varchar(7) DEFAULT NULL,
  `xMun` varchar(60) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  `CEP` char(8) DEFAULT NULL,
  `cPais` varchar(4) DEFAULT NULL,
  `xPais` varchar(60) DEFAULT NULL,
  `fone` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`enderDest_id`),
  KEY `dest_id` (`dest_id`),
  CONSTRAINT `enderDest_ibfk_1` FOREIGN KEY (`dest_id`) REFERENCES `dest` (`dest_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'enderDest'
#

# (No data found.)



#
# Table structure for table 'enderEmit'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `enderEmit` (
  `enderEmit_id` int(11) NOT NULL AUTO_INCREMENT,
  `emit_id` int(11) DEFAULT NULL,
  `xLgr` varchar(60) DEFAULT NULL,
  `nro` varchar(60) DEFAULT NULL,
  `xCpl` varchar(60) DEFAULT NULL,
  `xBairro` varchar(60) DEFAULT NULL,
  `cMun` varchar(7) DEFAULT NULL,
  `xMun` varchar(60) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  `CEP` char(8) DEFAULT NULL,
  `cPais` varchar(4) DEFAULT NULL,
  `xPais` varchar(60) DEFAULT NULL,
  `fone` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`enderEmit_id`),
  KEY `emit_id` (`emit_id`),
  CONSTRAINT `enderEmit_ibfk_1` FOREIGN KEY (`emit_id`) REFERENCES `emit` (`emit_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'enderEmit'
#

# (No data found.)



#
# Table structure for table 'entrega'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `entrega` (
  `entrega_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `CPF` varchar(11) DEFAULT NULL,
  `xLgr` varchar(60) DEFAULT NULL,
  `nro` varchar(60) DEFAULT NULL,
  `xCpl` varchar(60) DEFAULT NULL,
  `xBairro` varchar(60) DEFAULT NULL,
  `cMun` varchar(7) DEFAULT NULL,
  `xMun` varchar(60) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  PRIMARY KEY (`entrega_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'entrega'
#

# (No data found.)



#
# Table structure for table 'exporta'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `exporta` (
  `exporta_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `UFEmbarq` char(2) DEFAULT NULL,
  `xLocEmbarq` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`exporta_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `exporta_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'exporta'
#

# (No data found.)



#
# Table structure for table 'fat'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `fat` (
  `fat_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `nFat` varchar(60) DEFAULT NULL,
  `vOrig` double DEFAULT NULL,
  `vDesc` double DEFAULT NULL,
  `vLiq` double DEFAULT NULL,
  PRIMARY KEY (`fat_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `fat_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'fat'
#

# (No data found.)



#
# Table structure for table 'ide'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `ide` (
  `ide_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `cUF` char(2) DEFAULT NULL,
  `cNF` int(11) DEFAULT NULL,
  `natOp` varchar(60) DEFAULT NULL,
  `indPag` char(1) DEFAULT NULL,
  `mod` varchar(2) DEFAULT NULL,
  `serie` varchar(3) DEFAULT NULL,
  `nNF` int(11) DEFAULT NULL,
  `dEmi` date DEFAULT NULL,
  `dSaiEnt` date DEFAULT NULL,
  `hSaiEnt` time DEFAULT '00:00:00',
  `tpNF` char(1) DEFAULT NULL,
  `cMunFG` char(7) DEFAULT NULL,
  `tpImp` char(1) DEFAULT NULL,
  `tpEmis` int(3) DEFAULT NULL,
  `cDV` int(11) DEFAULT NULL,
  `tpAmb` char(1) DEFAULT NULL,
  `finNFe` char(1) DEFAULT NULL,
  `procEmi` char(1) DEFAULT NULL,
  `verProc` varchar(20) DEFAULT NULL,
  `dhCont` varchar(20) DEFAULT NULL,
  `xJust` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ide_id`),
  KEY `NFe_id` (`NFe_id`),
  KEY `tpEmis` (`tpEmis`),
  CONSTRAINT `ide_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ide_ibfk_2` FOREIGN KEY (`tpEmis`) REFERENCES `tpEmis` (`tpEmis`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'ide'
#

# (No data found.)



#
# Table structure for table 'infAdic'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `infAdic` (
  `infAdic_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `infAdFisco` text,
  `infCpl` text,
  PRIMARY KEY (`infAdic_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `infAdic_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'infAdic'
#

# (No data found.)



#
# Table structure for table 'issqntot'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `issqntot` (
  `ISSQNtot_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `vSer` double DEFAULT NULL,
  `vBC` double DEFAULT NULL,
  `vISS` double DEFAULT NULL,
  `vPIS` double DEFAULT NULL,
  `vCOFINS` double DEFAULT NULL,
  PRIMARY KEY (`ISSQNtot_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `issqntot_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'issqntot'
#

# (No data found.)



#
# Table structure for table 'lacres'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `lacres` (
  `lacres_id` int(11) NOT NULL AUTO_INCREMENT,
  `vol_id` int(11) DEFAULT NULL,
  `nLacre` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`lacres_id`),
  KEY `vol_id` (`vol_id`),
  CONSTRAINT `lacres_ibfk_1` FOREIGN KEY (`vol_id`) REFERENCES `vol` (`vol_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'lacres'
#

# (No data found.)



#
# Table structure for table 'med'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `med` (
  `med_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `nLote` varchar(20) DEFAULT NULL,
  `qLote` double DEFAULT NULL,
  `dFab` date DEFAULT NULL,
  `dVal` date DEFAULT NULL,
  `vPMC` double DEFAULT NULL,
  PRIMARY KEY (`med_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `med_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'med'
#

# (No data found.)



#
# Table structure for table 'obsCont'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `obsCont` (
  `obsCont_id` int(11) NOT NULL AUTO_INCREMENT,
  `infAdic_id` int(11) DEFAULT NULL,
  `xCampo` varchar(20) DEFAULT NULL,
  `xTexto` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`obsCont_id`),
  KEY `infAdic_id` (`infAdic_id`),
  CONSTRAINT `obsCont_ibfk_1` FOREIGN KEY (`infAdic_id`) REFERENCES `infAdic` (`infAdic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'obsCont'
#

# (No data found.)



#
# Table structure for table 'obsFisco'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `obsFisco` (
  `obsFisco_id` int(11) NOT NULL AUTO_INCREMENT,
  `infAdic_id` int(11) DEFAULT NULL,
  `xCampo` varchar(20) DEFAULT NULL,
  `xTexto` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`obsFisco_id`),
  KEY `infAdic_id` (`infAdic_id`),
  CONSTRAINT `obsFisco_ibfk_1` FOREIGN KEY (`infAdic_id`) REFERENCES `infAdic` (`infAdic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'obsFisco'
#

# (No data found.)



#
# Table structure for table 'procRef'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `procRef` (
  `procRef_id` int(11) NOT NULL AUTO_INCREMENT,
  `infAdic_id` int(11) DEFAULT NULL,
  `nProc` varchar(60) DEFAULT NULL,
  `indProc` char(1) DEFAULT NULL,
  PRIMARY KEY (`procRef_id`),
  KEY `infAdic_id` (`infAdic_id`),
  CONSTRAINT `procRef_ibfk_1` FOREIGN KEY (`infAdic_id`) REFERENCES `infAdic` (`infAdic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'procRef'
#

# (No data found.)



#
# Table structure for table 'prod'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `prod` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `det_id` int(11) DEFAULT NULL,
  `nItem` tinyint(3) unsigned DEFAULT NULL,
  `cProd` varchar(60) DEFAULT NULL,
  `cEAN` varchar(14) DEFAULT NULL,
  `xProd` varchar(120) DEFAULT NULL,
  `NCM` char(8) DEFAULT NULL,
  `EXTIPI` varchar(3) DEFAULT NULL,
  `genero` char(2) DEFAULT NULL,
  `CFOP` char(4) DEFAULT NULL,
  `uCom` varchar(6) DEFAULT NULL,
  `qCom` double DEFAULT NULL,
  `vUnCom` double DEFAULT NULL,
  `vProd` double DEFAULT NULL,
  `cEANTrib` varchar(14) DEFAULT NULL,
  `uTrib` varchar(6) DEFAULT NULL,
  `qTrib` double DEFAULT NULL,
  `vUnTrib` double DEFAULT NULL,
  `vFrete` double DEFAULT NULL,
  `vSeg` double DEFAULT NULL,
  `vDesc` double DEFAULT NULL,
  `indTot` tinyint(3) DEFAULT NULL,
  `vOutro` double DEFAULT NULL,
  `xPed` varchar(15) DEFAULT NULL,
  `nItemPed` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`prod_id`),
  KEY `det_id` (`det_id`),
  CONSTRAINT `prod_ibfk_1` FOREIGN KEY (`det_id`) REFERENCES `det` (`det_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'prod'
#

# (No data found.)



#
# Table structure for table 'protNFe'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `protNFe` (
  `protNFe_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `Id` varchar(46) DEFAULT NULL,
  `tpAmb` char(1) DEFAULT NULL,
  `verAplic` varchar(20) DEFAULT NULL,
  `chNFe` varchar(44) DEFAULT NULL,
  `dRecbto` date DEFAULT NULL,
  `hRecbto` time DEFAULT NULL,
  `nProt` varchar(15) DEFAULT NULL,
  `DIgVal` varchar(28) DEFAULT NULL,
  `cStat` char(3) DEFAULT NULL,
  `xMotivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`protNFe_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `protNFe_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'protNFe'
#

# (No data found.)



#
# Table structure for table 'reboque'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `reboque` (
  `reboque_id` int(11) NOT NULL AUTO_INCREMENT,
  `transp_id` int(11) DEFAULT NULL,
  `placa` varchar(8) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  `RNTC` char(20) DEFAULT NULL,
  `vagao` varchar(20) DEFAULT NULL,
  `balsa` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`reboque_id`),
  KEY `transp_id` (`transp_id`),
  CONSTRAINT `reboque_ibfk_1` FOREIGN KEY (`transp_id`) REFERENCES `transp` (`transp_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'reboque'
#

# (No data found.)



#
# Table structure for table 'refECF'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `refECF` (
  `refECF_id` int(11) NOT NULL AUTO_INCREMENT,
  `ide_id` int(11) DEFAULT NULL,
  `mod` char(2) DEFAULT NULL,
  `nECF` int(3) DEFAULT NULL,
  `nCOO` int(6) DEFAULT NULL,
  PRIMARY KEY (`refECF_id`),
  KEY `ide_id` (`ide_id`),
  CONSTRAINT `refECF_ibfk_1` FOREIGN KEY (`ide_id`) REFERENCES `ide` (`ide_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'refECF'
#

# (No data found.)



#
# Table structure for table 'refNF'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `refNF` (
  `refNF_id` int(11) NOT NULL AUTO_INCREMENT,
  `ide_id` int(11) DEFAULT NULL,
  `cUF` char(2) DEFAULT NULL,
  `AAMM` char(4) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `mod` char(2) DEFAULT NULL,
  `serie` varchar(3) DEFAULT NULL,
  `nNF` int(11) DEFAULT NULL,
  PRIMARY KEY (`refNF_id`),
  KEY `ide_id` (`ide_id`),
  CONSTRAINT `refNF_ibfk_1` FOREIGN KEY (`ide_id`) REFERENCES `ide` (`ide_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'refNF'
#

# (No data found.)



#
# Table structure for table 'refNFP'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `refNFP` (
  `refNFP_id` int(11) NOT NULL AUTO_INCREMENT,
  `ide_id` int(11) DEFAULT NULL,
  `cUF` char(2) DEFAULT NULL,
  `AAMM` char(4) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `IE` varchar(14) DEFAULT NULL,
  `mod` char(2) DEFAULT NULL,
  `serie` varchar(3) DEFAULT NULL,
  `nNF` int(11) DEFAULT NULL,
  PRIMARY KEY (`refNFP_id`),
  KEY `ide_id` (`ide_id`),
  CONSTRAINT `refNFP_ibfk_1` FOREIGN KEY (`ide_id`) REFERENCES `ide` (`ide_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'refNFP'
#

# (No data found.)



#
# Table structure for table 'refNFe'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `refNFe` (
  `refNFe_id` int(11) NOT NULL AUTO_INCREMENT,
  `ide_id` int(11) DEFAULT NULL,
  `refNFe` char(44) DEFAULT NULL,
  `refCTe` varchar(44) DEFAULT NULL,
  PRIMARY KEY (`refNFe_id`),
  KEY `ide_id` (`ide_id`),
  CONSTRAINT `refNFe_ibfk_1` FOREIGN KEY (`ide_id`) REFERENCES `ide` (`ide_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'refNFe'
#

# (No data found.)



#
# Table structure for table 'retTransp'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `retTransp` (
  `retTransp_id` int(11) NOT NULL AUTO_INCREMENT,
  `transp_id` int(11) DEFAULT NULL,
  `vServ` double DEFAULT NULL,
  `vBCRet` double DEFAULT NULL,
  `pICMSRet` double DEFAULT NULL,
  `vICMSRet` double DEFAULT NULL,
  `CFOP` char(4) DEFAULT NULL,
  `cMunFG` char(7) DEFAULT NULL,
  PRIMARY KEY (`retTransp_id`),
  KEY `transp_id` (`transp_id`),
  CONSTRAINT `retTransp_ibfk_1` FOREIGN KEY (`transp_id`) REFERENCES `transp` (`transp_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'retTransp'
#

# (No data found.)



#
# Table structure for table 'retTrib'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `retTrib` (
  `retTrib_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `vRetPIS` double DEFAULT NULL,
  `vRetCOFINS` double DEFAULT NULL,
  `vRetCSLL` double DEFAULT NULL,
  `vBCIRRF` double DEFAULT NULL,
  `vIRRF` double DEFAULT NULL,
  `vBCRetPrev` double DEFAULT NULL,
  `vRetPrev` double DEFAULT NULL,
  PRIMARY KEY (`retTrib_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `retTrib_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'retTrib'
#

# (No data found.)



#
# Table structure for table 'retirada'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `retirada` (
  `retirada_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `CPF` varchar(11) DEFAULT NULL,
  `xLgr` varchar(60) DEFAULT NULL,
  `nro` varchar(60) DEFAULT NULL,
  `xCpl` varchar(60) DEFAULT NULL,
  `xBairro` varchar(60) DEFAULT NULL,
  `cMun` varchar(7) DEFAULT NULL,
  `xMun` varchar(60) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  PRIMARY KEY (`retirada_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `retirada_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'retirada'
#

# (No data found.)



#
# Table structure for table 'situacao'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `situacao` (
  `situacao_id` int(3) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`situacao_id`)
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'situacao'
#

LOCK TABLES `situacao` WRITE;
/*!40000 ALTER TABLE `situacao` DISABLE KEYS*/;
REPLACE INTO `situacao` (`situacao_id`, `descricao`) VALUES
	(1,'Assinada'),
	(2,'Autorizada'),
	(3,'Cancelada'),
	(4,'Denegada'),
	(5,'Em Digitação'),
	(6,'Em Processamento na SEFAZ'),
	(7,'Rejeitada'),
	(8,'Transmitida com Pendência'),
	(9,'Validada');
/*!40000 ALTER TABLE `situacao` ENABLE KEYS*/;
UNLOCK TABLES;


#
# Table structure for table 'tpEmis'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tpEmis` (
  `tpEmis` int(3) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tpEmis`)
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'tpEmis'
#

LOCK TABLES `tpEmis` WRITE;
/*!40000 ALTER TABLE `tpEmis` DISABLE KEYS*/;
REPLACE INTO `tpEmis` (`tpEmis`, `descricao`) VALUES
	(1,'Normal'),
	(2,'Contingência FS'),
	(3,'Contingência com SCAN'),
	(4,'Contingência via DPEC'),
	(5,'Contingência FS-DA');
/*!40000 ALTER TABLE `tpEmis` ENABLE KEYS*/;
UNLOCK TABLES;


#
# Table structure for table 'transp'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `transp` (
  `transp_id` int(11) NOT NULL AUTO_INCREMENT,
  `NFe_id` int(11) DEFAULT NULL,
  `modFrete` char(1) DEFAULT NULL,
  PRIMARY KEY (`transp_id`),
  KEY `NFe_id` (`NFe_id`),
  CONSTRAINT `transp_ibfk_1` FOREIGN KEY (`NFe_id`) REFERENCES `NFe` (`NFe_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'transp'
#

# (No data found.)



#
# Table structure for table 'transporta'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `transporta` (
  `transporta_id` int(11) NOT NULL AUTO_INCREMENT,
  `transp_id` int(11) DEFAULT NULL,
  `CNPJ` char(14) DEFAULT NULL,
  `CPF` char(11) DEFAULT NULL,
  `xNome` varchar(60) DEFAULT NULL,
  `IE` varchar(14) DEFAULT NULL,
  `xEnder` varchar(60) DEFAULT NULL,
  `xMun` varchar(60) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  PRIMARY KEY (`transporta_id`),
  KEY `transp_id` (`transp_id`),
  CONSTRAINT `transporta_ibfk_1` FOREIGN KEY (`transp_id`) REFERENCES `transp` (`transp_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'transporta'
#

# (No data found.)



#
# Table structure for table 'veicProd'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `veicProd` (
  `veicProd_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `tpOp` int(11) DEFAULT NULL,
  `chassi` varchar(17) DEFAULT NULL,
  `cCor` varchar(4) DEFAULT NULL,
  `xCor` varchar(40) DEFAULT NULL,
  `pot` varchar(4) DEFAULT NULL,
  `cilin` varchar(4) DEFAULT NULL,
  `CM3` varchar(4) DEFAULT NULL,
  `pesoL` varchar(9) DEFAULT NULL,
  `pesoB` varchar(9) DEFAULT NULL,
  `nSerie` varchar(9) DEFAULT NULL,
  `tpComb` varchar(8) DEFAULT NULL,
  `nMotor` varchar(21) DEFAULT NULL,
  `CMT` varchar(9) DEFAULT NULL,
  `CMKG` varchar(9) DEFAULT NULL,
  `dist` varchar(4) DEFAULT NULL,
  `RENAVAM` varchar(9) DEFAULT NULL,
  `anoMod` int(11) DEFAULT NULL,
  `anoFab` int(11) DEFAULT NULL,
  `tpPint` char(1) DEFAULT NULL,
  `tpVeic` int(11) DEFAULT NULL,
  `espVeic` int(11) DEFAULT NULL,
  `VIN` char(1) DEFAULT NULL,
  `condVeic` int(11) DEFAULT NULL,
  `cMod` int(11) DEFAULT NULL,
  `cCorDENATRAN` tinyint(2) DEFAULT NULL,
  `lota` tinyint(3) DEFAULT NULL,
  `tpRest` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`veicProd_id`),
  KEY `prod_id` (`prod_id`),
  CONSTRAINT `veicProd_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `prod` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'veicProd'
#

# (No data found.)



#
# Table structure for table 'veicTransp'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `veicTransp` (
  `veicTransp_id` int(11) NOT NULL AUTO_INCREMENT,
  `transp_id` int(11) DEFAULT NULL,
  `placa` varchar(8) DEFAULT NULL,
  `UF` char(2) DEFAULT NULL,
  `RNTC` char(20) DEFAULT NULL,
  PRIMARY KEY (`veicTransp_id`),
  KEY `transp_id` (`transp_id`),
  CONSTRAINT `veicTransp_ibfk_1` FOREIGN KEY (`transp_id`) REFERENCES `transp` (`transp_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'veicTransp'
#

# (No data found.)



#
# Table structure for table 'vol'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `vol` (
  `vol_id` int(11) NOT NULL AUTO_INCREMENT,
  `transp_id` int(11) DEFAULT NULL,
  `qVol` double DEFAULT NULL,
  `esp` varchar(60) DEFAULT NULL,
  `marca` varchar(60) DEFAULT NULL,
  `nVol` varchar(60) DEFAULT NULL,
  `pesoL` double DEFAULT NULL,
  `pesoB` double DEFAULT NULL,
  PRIMARY KEY (`vol_id`),
  KEY `transp_id` (`transp_id`),
  CONSTRAINT `vol_ibfk_1` FOREIGN KEY (`transp_id`) REFERENCES `transp` (`transp_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'vol'
#

# (No data found.)

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS*/;

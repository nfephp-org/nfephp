-- dfadel, 10/07/2009
-- nfe.sql
-- criacao das tabelas para armazenamento de dados da NFe e dados de retorno

DROP TABLE IF EXISTS situacao;
CREATE TABLE situacao (
    situacao_id         INTEGER PRIMARY KEY AUTO_INCREMENT,
    descricao           VARCHAR(50)
) ENGINE=InnoDB;

INSERT INTO situacao VALUES (1, 'Assinada');
INSERT INTO situacao VALUES (2, 'Autorizada');
INSERT INTO situacao VALUES (3, 'Cancelada');
INSERT INTO situacao VALUES (4, 'Denegada');
INSERT INTO situacao VALUES (5, 'Em Digitação');
INSERT INTO situacao VALUES (6, 'Em Processamento na SEFAZ');
INSERT INTO situacao VALUES (7, 'Rejeitada');
INSERT INTO situacao VALUES (8, 'Transmitida com Pendência');
INSERT INTO situacao VALUES (9, 'Validada');


DROP TABLE IF EXISTS tpEmis;
CREATE TABLE tpEmis (
    tpEmis          INTEGER PRIMARY KEY AUTO_INCREMENT,
    descricao       VARCHAR(50)
) ENGINE=innoDB;
INSERT INTO tpEmis VALUES (1, 'Normal');
INSERT INTO tpEmis VALUES (2, 'Contingência FS');
INSERT INTO tpEmis VALUES (3, 'Contingência com SCAN');
INSERT INTO tpEmis VALUES (4, 'Contingência via DPEC');
INSERT INTO tpEmis VALUES (5, 'Contingência FS-DA');



DROP TABLE IF EXISTS NFe;
CREATE TABLE NFe (
    NFe_id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    situacao_id         INTEGER,
    FOREIGN KEY (situacao_id) REFERENCES situacao(situacao_id)
) ENGINE=InnoDB;

--------------------------------------------------------------------------------
-- tabelas dados extras e retornos
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS NFe_extras;
CREATE TABLE NFe_extras (
    NFe_extras_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    NFe_id              INTEGER,
    path_xml            VARCHAR(255),
    FOREIGN KEY (NFe_id) REFERENCES NFe(NFe_id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS retEnvNFe;
CREATE TABLE retEnvNFe (
    retEnvNFe_id        INTEGER PRIMARY KEY AUTO_INCREMENT,
    NFe_id              INTEGER,
    tpAmb               CHAR(1),
    verAplic            VARCHAR(20),
    cStat               CHAR(3),
    xMotivo             VARCHAR(255),
    cUF                 CHAR(2),
    nRec                VARCHAR(15),
    dRecbto             DATE,
    hRecbto             TIME,
    tMed                INTEGER,
    FOREIGN KEY (NFe_id) REFERENCES NFe(NFe_id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS protNFe;
CREATE TABLE protNFe (
    protNFe_id          INTEGER PRIMARY KEY AUTO_INCREMENT,
    NFe_id              INTEGER,
    Id                  VARCHAR(46),
    tpAmb               CHAR(1),
    verAplic            VARCHAR(20),
    chNFe               VARCHAR(44),
    dRecbto             DATE,
    hRecbto             TIME,
    nProt               VARCHAR(15),
    digVal              VARCHAR(28),
    cStat               CHAR(3),
    xMotivo             VARCHAR(255),
    FOREIGN KEY (NFe_id) REFERENCES NFe(NFe_id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS retCancNFe;
CREATE TABLE retCancNFe (
    retCancNFe_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    NFe_id              INTEGER,
    Id                  VARCHAR(46),
    tpAmb               CHAR(1),
    verAplic            VARCHAR(20),
    cStat               CHAR(3),
    xMotivo             VARCHAR(255),
    cUF                 CHAR(2),
    chNFe               VARCHAR(44),
    dRecbto             DATE,
    hRecbto             TIME,
    nProt               VARCHAR(15),
    xJust               VARCHAR(255),
    FOREIGN KEY (NFe_id) REFERENCES NFe(NFe_id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS retInutNFe;
CREATE TABLE retInutNFe (
    retInutNFe_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    Id                  VARCHAR(46),
    tpAmb               CHAR(1),
    cStat               CHAR(3),
    xMotivo             VARCHAR(255),
    cUF                 CHAR(2),
    ano                 CHAR(2),
    CNPJ                CHAR(14),
    modelo              CHAR(2),
    serie               CHAR(3),
    nNFIni              INTEGER,
    nNFFin              INTEGER,
    dRecbto             DATE,
    hRecbto             TIME,
    nProt               VARCHAR(15),
    xJust               VARCHAR(255)
) ENGINE=InnoDB;
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS infNFe;
CREATE TABLE infNFe (
    infNFe_id           INTEGER PRIMARY KEY AUTO_INCREMENT,
    NFe_id              INTEGER,
    versao              VARCHAR(4),
    Id                  VARCHAR(50),
    FOREIGN KEY (NFe_id) REFERENCES NFe(NFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS det;
CREATE TABLE det (
    det_id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id           INTEGER,
    nItem               INTEGER,
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS entrega;
CREATE TABLE entrega (
    entrega_id          INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id           INTEGER,
    CNPJ                CHAR(14),
    xLgr                VARCHAR(60),
    nro                 VARCHAR(60),
    xCpl                VARCHAR(60),
    xBairro             VARCHAR(60),
    cMun                VARCHAR(7),
    xMun                VARCHAR(60),
    UF                  CHAR(2),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS retirada;
CREATE TABLE retirada (
    retirada_id         INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id           INTEGER,
    CNPJ                CHAR(14),
    xLgr                VARCHAR(60),
    nro                 VARCHAR(60),
    xCpl                VARCHAR(60),
    xBairro             VARCHAR(60),
    cMun                VARCHAR(7),
    xMun                VARCHAR(60),
    UF                  CHAR(2),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS dest;
CREATE TABLE dest (
    dest_id             INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id           INTEGER,
    CNPJ                CHAR(14),
    CPF                 CHAR(11),
    xNome               VARCHAR(60),
    IE                  VARCHAR(14),
    ISUF                CHAR(9),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS avulsa;
CREATE TABLE avulsa (
    avulsa_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id     INTEGER,
    CNPJ           CHAR(14),
    xOrgao       VARCHAR(60),
    matr          VARCHAR(60),
    xAgente     VARCHAR(60),
    fone          VARCHAR(10),
    UF             CHAR(2),
    nDAR         VARCHAR(60),
    dEmi         DATE,
    vDAR         VARCHAR(15),
    repEmi      VARCHAR(60),
    dPag         DATE,
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS emit;
CREATE TABLE emit (
    emit_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id    INTEGER,
    CNPJ           CHAR(14),
    CPF            CHAR(11),
    xNome       VARCHAR(60),
    xFant         VARCHAR(60),
    IE              VARCHAR(14),
    IEST          VARCHAR(14),
    IM             VARCHAR(15),
    CNAE        CHAR(7),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS ide;
CREATE TABLE ide (
    ide_id          INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id       INTEGER,
    cUF             CHAR(2),
    cNF             INTEGER,
    natOp           VARCHAR(60),
    indPag          CHAR(1),
    modelo          VARCHAR(2),
    serie           VARCHAR(3),
    nNF             INTEGER,
    dEmi            DATE,
    dSaiEnt         DATE,
    tpNF            CHAR(1),
    cMunFG          CHAR(7),
    tpImp           CHAR(1),
    tpEmis          INTEGER,
    cDV             INTEGER,
    tpAmb           CHAR(1),
    finNFe          CHAR(1),
    procEmi         CHAR(1),
    verProc         VARCHAR(20),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE,
    FOREIGN KEY (tpEmis) REFERENCES tpEmis(tpEmis)
) ENGINE=innoDB;


DROP TABLE IF EXISTS total;
CREATE TABLE total (
    total_id        INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id       INTEGER,
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS transp;
CREATE TABLE transp (
    transp_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id       INTEGER,
    modFrete        CHAR(1),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS cobr;
CREATE TABLE cobr (
    cobr_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id  INTEGER,
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS infAdic;
CREATE TABLE infAdic (
    infAdic_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id       INTEGER,
    infAdFisco  VARCHAR(256),
    infCpl            TEXT,
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS exporta;
CREATE TABLE exporta (
    exporta_id      INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id       INTEGER,
    UFEmbarq        CHAR(2),
    xLocEmbarq      VARCHAR(60),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS compra;
CREATE TABLE compra (
    compra_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    infNFe_id       INTEGER,
    xNEmp          VARCHAR(17),
    xPed             VARCHAR(60),
    xCont            VARCHAR(60),
    FOREIGN KEY (infNFe_id) REFERENCES infNFe(infNFe_id) ON DELETE CASCADE
) ENGINE=innoDB;
 
  
DROP TABLE IF EXISTS refNFe;
CREATE TABLE refNFe (
    refNFe_id   INTEGER PRIMARY KEY AUTO_INCREMENT,
    ide_id         INTEGER,
    refNFe       CHAR(44),
    FOREIGN KEY (ide_id) REFERENCES ide(ide_id) ON DELETE CASCADE
) ENGINE=innoDB;  


DROP TABLE IF EXISTS refNF;
CREATE TABLE refNF (
    refNF_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    ide_id         INTEGER,
    cUF            CHAR(2),
    AAMM         CHAR(4),
    CNPJ           CHAR(14),
    modelo           CHAR(2),
    serie          VARCHAR(3),
    nNF            INTEGER,
    FOREIGN KEY (ide_id) REFERENCES ide(ide_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS enderEmit;
CREATE TABLE enderEmit (
    enderEmir_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    emit_id      INTEGER,
    xLgr        VARCHAR(60),
    nro           VARCHAR(60),
    xCpl          VARCHAR(60),
    xBairro      VARCHAR(60),
    cMun         VARCHAR(7),
    xMun         VARCHAR(60),
    UF            CHAR(2),
    CEP          CHAR(8),
    cPais        VARCHAR(4),
    xPais        VARCHAR(60),
    fone         VARCHAR(10),
    FOREIGN KEY (emit_id) REFERENCES emit(emit_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS enderDest;
CREATE TABLE enderDest (
    enderDest_id  INTEGER PRIMARY KEY AUTO_INCREMENT,
    dest_id       INTEGER,
    xLgr          VARCHAR(60),
    nro            VARCHAR(60),
    xCpl           VARCHAR(60),
    xBairro       VARCHAR(60),
    cMun          VARCHAR(7),
    xMun          VARCHAR(60),
    UF             CHAR(2),
    CEP           CHAR(8),
    cPais         VARCHAR(4),
    xPais         VARCHAR(60),
    fone          VARCHAR(10),
    FOREIGN KEY (dest_id) REFERENCES dest(dest_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS prod;
CREATE TABLE prod (
    prod_id         INTEGER PRIMARY KEY AUTO_INCREMENT,
    det_id          INTEGER,
    cProd           VARCHAR(60),
    cEAN            VARCHAR(14),
    xProd           VARCHAR(120),
    NCM             CHAR(8),
    EXTIPI          VARCHAR(3),
    genero          CHAR(2),
    CFOP            CHAR(4),
    uCom            VARCHAR(6),
    qCom            DOUBLE,
    vUnCom          DOUBLE,
    vProd           DOUBLE,
    cEANTrib        VARCHAR(14),
    uTrib           VARCHAR(6),
    qTrib           DOUBLE,
    vUnTrib         DOUBLE,
    vFrete          DOUBLE,
    vSeg            DOUBLE,
    vDesc           DOUBLE,
    FOREIGN KEY (det_id) REFERENCES det(det_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS infAdProd;
CREATE TABLE infAdProd (
    infAdProd_id    INTEGER PRIMARY KEY AUTO_INCREMENT,
    det_id          INTEGER,
    infAdProd       TEXT,
    FOREIGN KEY (det_id) REFERENCES det(det_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS imposto;
CREATE TABLE imposto (
    imposto_id    INTEGER PRIMARY KEY AUTO_INCREMENT,
    det_id            INTEGER,
    FOREIGN KEY (det_id) REFERENCES det(det_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS ICMSTot;
CREATE TABLE ICMSTot (
    ICMSTot_id      INTEGER PRIMARY KEY AUTO_INCREMENT,
    total_id        INTEGER,
    vBC             DOUBLE,
    vICMS           DOUBLE,
    vBCST           DOUBLE,
    vST             DOUBLE,
    vProd           DOUBLE,
    vFrete          DOUBLE,
    vSeg            DOUBLE,
    vDesc           DOUBLE,
    vII             DOUBLE,
    vIPI            DOUBLE,
    vPIS            DOUBLE,
    vCOFINS         DOUBLE,
    vOutro          DOUBLE,
    vNF             DOUBLE,
    FOREIGN KEY (total_id) REFERENCES total(total_id) ON DELETE CASCADE
) ENGINE=innoDB;    


DROP TABLE IF EXISTS ISSQNtot;
CREATE TABLE ISSQNtot (
    ISSQNtot_id    INTEGER PRIMARY KEY AUTO_INCREMENT,
    total_id           INTEGER,
    vSer               DOUBLE,
    vBC                DOUBLE,
    vISS               DOUBLE,
    vPIS               DOUBLE,
    vCOFINS        DOUBLE,
    FOREIGN KEY (total_id) REFERENCES total(total_id) ON DELETE CASCADE
) ENGINE=innoDB;    


DROP TABLE IF EXISTS retTrib;
CREATE TABLE retTrib (
    retTrib_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    total_id          INTEGER,
    vRetPIS          DOUBLE,
    vRetCOFINS    DOUBLE,
    vRetCSLL        DOUBLE,
    vBCIRRF          DOUBLE,
    vIRRF              DOUBLE,
    vBCRetPrev    DOUBLE,
    vRetPrev        DOUBLE,
    FOREIGN KEY (total_id) REFERENCES total(total_id) ON DELETE CASCADE
) ENGINE=innoDB;    


DROP TABLE IF EXISTS transporta;
CREATE TABLE transporta (
    transporta_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    transp_id           INTEGER,
    CNPJ                  CHAR(14),
    CPF                   CHAR(11),
    xNome              VARCHAR(60),
    IE                      VARCHAR(14),
    xEnder              VARCHAR(60),
    xMun                 VARCHAR(60),
    UF                    CHAR(2),
    FOREIGN KEY (transp_id) REFERENCES transp(transp_id) ON DELETE CASCADE
) ENGINE=innoDB;  
      
    
DROP TABLE IF EXISTS retTransp;
CREATE TABLE retTransp (
    retTransp_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    transp_id          INTEGER,
    vServ                DOUBLE,
    vBCRet             DOUBLE,
    pICMSRet         DOUBLE,
    vICMSRet         DOUBLE,
    CFOP               CHAR(4),
    cMunFG           CHAR(7),
    FOREIGN KEY (transp_id) REFERENCES transp(transp_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS veicTransp;
CREATE TABLE veicTransp (
    veicTransp_id   INTEGER PRIMARY KEY AUTO_INCREMENT,
    transp_id       INTEGER,
    placa           VARCHAR(8),
    UF              CHAR(2),
    RNTC            CHAR(20),
    FOREIGN KEY (transp_id) REFERENCES transp(transp_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS reboque;
CREATE TABLE reboque (
    reboque_id      INTEGER PRIMARY KEY AUTO_INCREMENT,
    transp_id       INTEGER,
    placa           VARCHAR(8),
    UF              CHAR(2),
    RNTC            CHAR(20),
    FOREIGN KEY (transp_id) REFERENCES transp(transp_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS vol;
CREATE TABLE vol (
    vol_id           INTEGER PRIMARY KEY AUTO_INCREMENT,
    transp_id     INTEGER,
    qVol             DOUBLE,
    esp              VARCHAR(60),
    marca          VARCHAR(60),
    nVol             VARCHAR(60),
    pesoL          DOUBLE,
    pesoB          DOUBLE,
    FOREIGN KEY (transp_id) REFERENCES transp(transp_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS fat;
CREATE TABLE fat (
    fat_id        INTEGER PRIMARY KEY AUTO_INCREMENT,
    cobr_id     INTEGER,
    nFat         VARCHAR(60),
    vOrig        DOUBLE,
    vDesc      DOUBLE,
    vLiq         DOUBLE,
    FOREIGN KEY (cobr_id) REFERENCES cobr(cobr_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS dup;
CREATE TABLE dup (
    dup_id    INTEGER PRIMARY KEY AUTO_INCREMENT,
    cobr_id   INTEGER,
    nDup      VARCHAR(60),
    dVenc    DATE,
    vDup      DOUBLE,
    FOREIGN KEY (cobr_id) REFERENCES cobr(cobr_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS obsCont;
CREATE TABLE obsCont (
    obsCont_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    infAdic_id        INTEGER,
    xCampo          VARCHAR(20),
    xTexto            VARCHAR(60),
    FOREIGN KEY (infAdic_id) REFERENCES infAdic(infAdic_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS obsFisco;
CREATE TABLE obsFisco (
    obsFisco_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    infAdic_id         INTEGER,
    xCampo           VARCHAR(20),
    xTexto             VARCHAR(60),
    FOREIGN KEY (infAdic_id) REFERENCES infAdic(infAdic_id) ON DELETE CASCADE
) ENGINE=innoDB; 


DROP TABLE IF EXISTS procRef;
CREATE TABLE procRef (
    procRef_id    INTEGER PRIMARY KEY AUTO_INCREMENT,
    infAdic_id      INTEGER,
    nProc           VARCHAR(60),
    indProc        CHAR(1),
    FOREIGN KEY (infAdic_id) REFERENCES infAdic(infAdic_id) ON DELETE CASCADE
) ENGINE=innoDB;         
    
    

DROP TABLE IF EXISTS ICMS;
CREATE TABLE ICMS (
    ICMS_id         INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id      INTEGER,
    orig            CHAR(1),
    CST             CHAR(2),
    modBC           CHAR(1),
    pRedBC          DOUBLE,
    vBC             DOUBLE,
    pICMS           DOUBLE,
    vICMS           DOUBLE,
    modBCST         CHAR(1),
    pMVAST          DOUBLE,
    pRedBCST        DOUBLE,
    vBCST           DOUBLE,
    pICMSST         DOUBLE,
    vICMSST         DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS IPI;
CREATE TABLE IPI (
    IPI_id            INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id   INTEGER,
    cIEnq           CHAR(5),
    CNPJProd     CHAR(14),
    cSelo           VARCHAR(60),
    qSelo          DOUBLE,
    cEnq           CHAR(3),
    CST            CHAR(2),
    vBC            DOUBLE,
    qUnid         DOUBLE,
    vUnid         DOUBLE,
    pIPI           DOUBLE,
    vIPI           DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;



DROP TABLE IF EXISTS II;
CREATE TABLE II (
    II_id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id   INTEGER,
    vBC               DOUBLE,
    vDespAdu     DOUBLE,
    vII                  DOUBLE,
    vIOF               DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS PIS;
CREATE TABLE PIS (
    PIS_id           INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id   INTEGER,
    CST              CHAR(2),
    vBC                  DOUBLE,
    pPIS            DOUBLE,
    vPIS            DOUBLE,
    qBCProd       DOUBLE,
    vAliqProd      DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS PISST;
CREATE TABLE PISST (
    PISST_id       INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id   INTEGER,
    vBC             DOUBLE,
    pPIS            DOUBLE,
    qBCProd        DOUBLE,
    vAliqProd       DOUBLE,
    vPIS              DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS COFINS;
CREATE TABLE COFINS (
    COFINS_id   INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id  INTEGER,
    CST         CHAR(2),
    vBC         DOUBLE,
    pCOFINS     DOUBLE, 
    qBCProd     DOUBLE,
    vAliqProd   DOUBLE,
    vCOFINS     DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;

 
DROP TABLE IF EXISTS COFINSST;
CREATE TABLE COFINSST (
    COFINSST_id   INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id    INTEGER,
    vBC           DOUBLE,
    pCOFINS       DOUBLE,
    qBCProd       DOUBLE,
    vAliqProd     DOUBLE,
    vCOFINS       DOUBLE,
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS ISSQN;
CREATE TABLE ISSQN (
    ISSQN_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    imposto_id   INTEGER,
    vBC          DOUBLE,
    vAliq        DOUBLE,
    vISSQN       DOUBLE,
    cMunFG       CHAR(7),
    cListServ    VARCHAR(4),
    FOREIGN KEY (imposto_id) REFERENCES imposto(imposto_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS comb;
CREATE TABLE comb (
    comb_id         INTEGER PRIMARY KEY AUTO_INCREMENT,
    prod_id         INTEGER,
    cProdANP        VARCHAR(9),
    CODIF           VARCHAR(21),
    qTempo          DOUBLE,
    qBCprod         DOUBLE,
    vAliqProd       DOUBLE,
    vCIDE           DOUBLE,
    vBCICMS         DOUBLE,
    vICMS           DOUBLE,
    vBCICMSST       DOUBLE,
    vICMSST         DOUBLE,
    vBCICMSSTDest   DOUBLE,
    vICMSSTDest     DOUBLE,
    vBCICMSSTCons   DOUBLE,
    vICMSSTCons     DOUBLE,
    UFcons          CHAR(2),
    FOREIGN KEY (prod_id) REFERENCES prod(prod_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS arma;
CREATE TABLE arma (
    arma_id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    prod_id     INTEGER,
    tpArma      INTEGER,
    nSerie      VARCHAR(9),
    nCano       VARCHAR(9),
    descr       VARCHAR(256),
    FOREIGN KEY (prod_id) REFERENCES prod(prod_id) ON DELETE CASCADE
) ENGINE=innoDB;



DROP TABLE IF EXISTS med;
CREATE TABLE med (
    med_id      INTEGER PRIMARY KEY AUTO_INCREMENT,
    prod_id     INTEGER,
    nLote       VARCHAR(20),
    qLote       DOUBLE,
    dFab        DATE,
    dVal        DATE,
    vPMC        DOUBLE,
    FOREIGN KEY (prod_id) REFERENCES prod(prod_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS veicProd;
CREATE TABLE veicProd (
    veicProd_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    prod_id     INTEGER,
    tpOp        INTEGER,
    chassi      VARCHAR(17),
    cCor        VARCHAR(4),
    xCor        VARCHAR(40),
    pot         VARCHAR(4),
    CM3         VARCHAR(4),
    pesoL       VARCHAR(9),
    pesoB       VARCHAR(9),
    nSerie      VARCHAR(9),
    tpComb      VARCHAR(8),
    nMotor      VARCHAR(21),
    CMKG        VARCHAR(9),
    dist        VARCHAR(4),
    RENAVAM     VARCHAR(9),
    anoMod      INTEGER,
    anoFab      INTEGER,
    tpPint      CHAR(1),
    tpVeic      INTEGER,
    espVeic     INTEGER,
    VIN         CHAR(1),
    condVeic    INTEGER,
    cMod        INTEGER,
    FOREIGN KEY (prod_id) REFERENCES prod(prod_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS DI;
CREATE TABLE DI (
    DI_id           INTEGER PRIMARY KEY AUTO_INCREMENT,
    prod_id         INTEGER,
    nDI             VARCHAR(10),
    dDi             DATE,
    xLocDesemb      VARCHAR(60),
    UFDesemb        CHAR(2),
    dDesemb         DATE,
    cExportador     VARCHAR(60),
    FOREIGN KEY (prod_id) REFERENCES prod(prod_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS lacres;
CREATE TABLE lacres (
    lacres_id    INTEGER PRIMARY KEY AUTO_INCREMENT,
    vol_id         INTEGER,
    nlacre        VARCHAR(60),
    FOREIGN KEY (vol_id) REFERENCES vol(vol_id) ON DELETE CASCADE
) ENGINE=innoDB;


DROP TABLE IF EXISTS adi;
CREATE TABLE adi (
    adi_id         INTEGER PRIMARY KEY AUTO_INCREMENT,
    DI_id           INTEGER,
    nAdicao     INTEGER,
    nSeqAdic    INTEGER,
    cFabricante VARCHAR(60),
    vDescDI     DOUBLE,
    FOREIGN KEY (DI_id) REFERENCES DI(DI_id) ON DELETE CASCADE
) ENGINE=innoDB;



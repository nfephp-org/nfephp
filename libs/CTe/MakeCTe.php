<?php

namespace NFePHP\CTe;

/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * Estrutura baseada nas notas técnicas:
 *
 *
 * @package     NFePHP
 * @name        MakeCTe
 * @version     0.0.2
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Renato R. Oliveira <developer.delliriun@gmail.com>
 *
 *
 */

use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Base\BaseMake;
use \DOMDocument;
use \DOMElement;

class MakeCTe extends BaseMake
{
    /**
     * versao
     * numero da versão do xml da CTe
     * @var string
     */
    public $versao = '2.00';
    /**
     * mod
     * modelo da CTe 57
     * @var integer
     */
    public $mod = 57;
    /**
     * chave da MDFe
     * @var string
     */
    public $chCTe = '';
    /**
     * xml
     * String com o xml do documento fiscal montado
     * @var string
     */
    public $xml = '';
    /**
     * dom
     * Variável onde será montado o xml do documento fiscal
     * @var \NFePHP\Common\Dom\Dom
     */
    public $dom;
    /**
     * tpAmb
     * tipo de ambiente
     * @var string
     */
    public $tpAmb = '2';
    /**
     * Modal do Cte
     * @var integer
     */
    private $modal = 0;
    /**
     * Tag CTe
     * @var \DOMNode
     */
    private $CTe = '';

    /**
     * Informações do CT-e
     * @var \DOMNode
     */
    private $infCte = '';
    /**
     * Identificação do CT-e
     * @var \DOMNode
     */
    private $ide = '';
    /**
     * Tipo do Serviço
     * @var integer
     */
    private $tpServ = 0;
    /**
     * Indicador do "papel" do tomador do serviço no CT-e
     * @var \DOMNode
     */
    private $toma03 = '';
    /**
     * Indicador do "papel" do tomador do serviço no CT-e
     * @var \DOMNode
     */
    private $toma4 = '';
    /**
     * Dados do endereço
     * @var \DOMNode
     */
    private $enderToma = '';
    /**
     * Dados complementares do CT-e para fins operacionais ou comerciais
     * @var \DOMNode
     */
    private $compl = '';
    /**
     * Previsão do fluxo da carga
     * @var \DOMNode
     */
    private $fluxo = '';
    /**
     * Passagem
     * @var array
     */
    private $pass = array();
    /**
     * Informações ref. a previsão de entrega
     * @var \DOMNode
     */
    private $entrega = '';
    /**
     * Entrega sem data definida
     * @var \DOMNode
     */
    private $semData = '';
    /**
     * Entrega com data definida
     * @var \DOMNode
     */
    private $comData = '';
    /**
     * Entrega no período definido
     * @var \DOMNode
     */
    private $noPeriodo = '';
    /**
     * Entrega sem hora definida
     * @var \DOMNode
     */
    private $semHora = '';
    /**
     * Entrega com hora definida
     * @var \DOMNode
     */
    private $comHora = '';
    /**
     * Entrega no intervalo de horário definido
     * @var \DOMNode
     */
    private $noInter = '';
    /**
     * Campo de uso livre do contribuinte
     * @var array
     */
    private $obsCont = array();
    /**
     * Campo de uso livre do contribuinte
     * @var array
     */
    private $obsFisco = array();
    /**
     * Identificação do Emitente do CT-e
     * @var \DOMNode
     */
    private $emit = '';
    /**
     * Endereço do emitente
     * @var \DOMNode
     */
    private $enderEmit = '';
    /**
     * Informações do Remetente das mercadorias transportadas pelo CT-e
     * @var \DOMNode
     */
    private $rem = '';
    /**
     * Dados do endereço
     * @var \DOMNode
     */
    private $enderReme = '';

    /**
     * Informações do Expedidor da Carga
     * @var \DOMNode
     */
    private $exped = '';
    /**
     * Dados do endereço
     * @var \DOMNode
     */
    private $enderExped = '';
    /**
     * Informações do Recebedor da Carga
     * @var \DOMNode
     */
    private $receb = '';
    /**
     * Dados do endereço
     * @var \DOMNode
     */
    private $enderReceb = '';
    /**
     * Informações do Destinatário do CT-e
     * @var \DOMNode
     */
    private $dest = '';
    /**
     * Dados do endereço
     * @var \DOMNode
     */
    private $enderDest = '';
    /**
     * Valores da Prestação de Serviço
     * @var \DOMNode
     */
    private $vPrest = '';
    /**
     * Componentes do Valor da Prestação
     * @var array
     */
    private $Comp = array();
    /**
     * Informações relativas aos Impostos
     * @var \DOMNode
     */
    private $imp = '';
    /**
     * Informações relativas ao ICMS
     * @var \DOMNode
     */
    private $ICMS = '';
    /**
     * Prestação sujeito à tributação normal do ICMS
     * @var \DOMNode
     */
    private $ICMS00 = '';
    /**
     * Prestação sujeito à tributação com redução de BC do ICMS
     * @var \DOMNode
     */
    private $ICMS20 = '';
    /**
     * ICMS Isento, não Tributado ou diferido
     * @var \DOMNode
     */
    private $ICMS45 = '';
    /**
     * Tributação pelo ICMS60 - ICMS cobrado por substituição tributária.
     * Responsabilidade do recolhimento do ICMS atribuído ao tomador ou 3º por ST
     * @var \DOMNode
     */
    private $ICMS60 = '';
    /**
     * ICMS Outros
     * @var \DOMNode
     */
    private $ICMS90 = '';
    /**
     * ICMS devido à UF de origem da prestação, quando diferente da UF do emitente
     * @var \DOMNode
     */
    private $ICMSOutraUF = '';
    /**
     * Simples Nacional
     * @var \DOMNode
     */
    private $ICMSSN = '';
    /**
     * Grupo de informações do CT-e Normal e Substituto
     * @var \DOMNode
     */
    private $infCTeNorm = '';
    /**
     * Informações da Carga do CT-e
     * @var \DOMNode
     */
    private $infCarga = '';
    /**
     * Informações de quantidades da Carga do CT-e
     * @var \DOMNode
     */
    private $infQ = array();
    /**
     * Informações dos documentos transportados pelo CT-e Opcional para Redespacho Intermediario
     * e Serviço vinculado a multimodal.
     * @var \DOMNode
     */
    private $infDoc = '';
    /**
     * Informações das NF
     * @var array
     */
    private $infNF = array();
    /**
     * Informações das NF-e
     * @var array
     */
    private $infNFe = array();
    /**
     * Informações dos demais documentos
     * @var array
     */
    private $infOutros = array();
    /**
     * Informações das Unidades de Transporte (Carreta/Reboque/Vagão)
     * @var array
     */
    private $infUnidTransp = array();
    /**
     * Lacres das Unidades de Transporte
     * @var array
     */
    private $lacUnidTransp = array();
    /**
     * Informações das Unidades de Carga (Containeres/ULD/Outros)
     * @var array
     */
    private $infUnidCarga = array();
    /**
     * Lacres das Unidades de Carga
     * @var array
     */
    private $lacUnidCarga = array();
    /**
     * Documentos de Transporte Anterior
     * @var \DOMNode
     */
    private $docAnt = '';
    /**
     * Emissor do documento anterior
     * @var array
     */
    private $emiDocAnt = array();
    /**
     * Informações de identificação dos documentos de Transporte Anterior
     * @var array
     */
    private $idDocAnt = array();
    /**
     * Documentos de transporte anterior em papel
     * @var array
     */
    private $idDocAntPap = array();
    /**
     * Documentos de transporte anterior eletrônicos
     * @var array
     */
    private $idDocAntEle = array();
    /**
     * Informações de Seguro da Carga
     * @var array
     */
    private $seg = array();
    /**
     * Informações do modal
     * @var \DOMNode
     */
    private $infModal = '';
    /**
     * Preenchido quando for transporte de produtos classificados pela ONU como perigosos.
     * @var array
     */
    private $peri = array();
    /**
     * informações dos veículos transportados
     * @var array
     */
    private $veicNovos = array();
    /**
     * Dados da cobrança do CT-e
     * @var \DOMNode
     */
    private $cobr = '';
    /**
     * Dados da fatura
     * @var \DOMNode
     */
    private $fat = '';
    /**
     * Dados das duplicatas
     * @var array
     */
    private $dup = array();
    /**
     * Informações do CT-e de substituição
     * @var \DOMNode
     */
    private $infCteSub = '';
    /**
     * Tomador é contribuinte do ICMS
     * @var \DOMNode
     */
    private $tomaICMS = '';
    /**
     * Tomador não é contribuinte do ICMS
     * @var \DOMNode
     */
    private $tomaNaoICMS = '';
    /**
     * Informação da NF ou CT emitido pelo Tomador
     * @var \DOMNode
     */
    private $refNF = '';
    /**
     * Informação do CTe emitido pelo Tomador
     * @var \DOMNode
     */
    private $refCte = '';
    /**
     * Informação da NF ou CT emitido pelo Tomador
     * @var \DOMNode
     */
    private $infCteComp = '';
    /**
     * Detalhamento do CT-e do tipo Anulação
     * @var \DOMNode
     */
    private $infCteAnu = '';
    /**
     * Informações do modal Rodoviário
     * @var \DOMNode
     */
    private $rodo = '';
    /**
     * Ordens de Coleta associados
     * @var array
     */
    private $occ = array();
    /**
     * @var \DOMNode
     */
    private $emiOcc = array();
    /**
     * Informações de Vale Pedágio
     * @var array
     */
    private $valePed = array();
    /**
     * Dados dos Veículos
     * @var array
     */
    private $veic = array();
    /**
     * Proprietários do Veículo. Só preenchido quando o veículo não pertencer à empresa emitente do CT-e
     * @var array
     */
    private $prop = array();
    /**
     * Dados dos Veículos
     * @var array
     */
    private $lacRodo = array();
    /**
     * Informações do(s) Motorista(s)
     * @var array
     */
    private $moto = array();

    /**
     * Monta o arquivo XML usando as tag's já preenchidas
     *
     * @return bool
     */
    public function montaCTe()
    {
        if (count($this->erros) > 0) {
            return false;
        }
        // cria a tag raiz
        $this->zCTeTag();
        if ($this->toma03 != '') {
            $this->dom->appChild($this->ide, $this->toma03, 'Falta tag "ide"');
        } else {
            $this->dom->appChild($this->toma4, $this->enderToma, 'Falta tag "toma4"');
            $this->dom->appChild($this->ide, $this->toma4, 'Falta tag "ide"');
        }
        $this->dom->appChild($this->infCte, $this->ide, 'Falta tag "infCte"');
        $this->dom->appChild($this->CTe, $this->infCte, 'Falta tag "CTe"');
        $this->dom->appChild($this->dom, $this->CTe, 'Falta tag "DOMDocument"');
        $this->xml = $this->dom->saveXML();
        return true;
    }

    /**
     * Gera o grupo básico: Informações do CT-e
     * #1
     * Nível: 0
     *
     * @param string $chave  Chave do CTe
     * @param string $versao Versão do CTe
     *
     * @return \DOMElement
     */
    public function infCteTag($chave = '', $versao = '')
    {
        $this->infCte = $this->dom->createElement('infCte');
        $this->infCte->setAttribute('Id', 'CTe' . $chave);
        $this->infCte->setAttribute('versao', $versao);
        return $this->infCte;
    }

    /**
     * Gera as tags para o elemento: Identificação do CT-e
     * #4
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "ide" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $cUF        Código da UF do emitente do CT-e
     * @param string $cCT        Código numérico que compõe a Chave de Acesso
     * @param string $CFOP       Código Fiscal de Operações e Prestações
     * @param string $natOp      Natureza da Operação
     * @param string $mod        Modelo do documento fiscal
     * @param string $serie      Série do CT-e
     * @param string $nCT        Número do CT-e
     * @param string $dhEmi      Data e hora de emissão do CT-e
     * @param string $tpImp      Formato de impressão do DACTE
     * @param string $tpEmis     Forma de emissão do CT-e
     * @param string $cDV        Digito Verificador da chave de acesso do CT-e
     * @param string $tpAmb      Tipo do Ambiente
     * @param string $tpCTe      Tipo do CT-e
     * @param string $procEmi    Identificador do processo de emissão do CT-e
     * @param string $verProc    Versão do processo de emissão
     * @param string $refCTE     Chave de acesso do CT-e referenciado
     * @param string $cMunEnv    Código do Município de envio do CT-e (de onde o documento foi transmitido)
     * @param string $xMunEnv    Nome do Município de envio do CT-e (de onde o documento foi transmitido)
     * @param string $UFEnv      Sigla da UF de envio do CT-e (de onde o documento foi transmitido)
     * @param string $modal      Modal
     * @param string $tpServ     Tipo do Serviço
     * @param string $cMunIni    Código do Município de início da prestação
     * @param string $xMunIni    Nome do Município do início da prestação
     * @param string $UFIni      UF do início da prestação
     * @param string $cMunFim    Código do Município de término da prestação
     * @param string $xMunFim    Nome do Município do término da prestação
     * @param string $UFFim      UF do término da prestação
     * @param string $retira     Indicador se o Recebedor retira no Aeroporto, Filial, Porto ou Estação de Destino?
     * @param string $xDetRetira Detalhes do retira
     * @param string $dhCont     Data e Hora da entrada em contingência
     * @param string $xJust      Justificativa da entrada em contingência
     *
     * @return \NFePHP\Common\Dom\Dom
     */
    public function ideTag(
        $cUF = '',
        $cCT = '',
        $CFOP = '',
        $natOp = '',
        $mod = '',
        $serie = '',
        $nCT = '',
        $dhEmi = '',
        $tpImp = '',
        $tpEmis = '',
        $cDV = '',
        $tpAmb = '',
        $tpCTe = '',
        $procEmi = '',
        $verProc = '',
        $refCTE = '',
        $cMunEnv = '',
        $xMunEnv = '',
        $UFEnv = '',
        $modal = '',
        $tpServ = '',
        $cMunIni = '',
        $xMunIni = '',
        $UFIni = '',
        $cMunFim = '',
        $xMunFim = '',
        $UFFim = '',
        $retira = '',
        $xDetRetira = '',
        $dhCont = '',
        $xJust = ''
    ) {
        $this->tpAmb = $tpAmb;
        $identificador = '#4 <ide> - ';
        $this->ide = $this->dom->createElement('ide');
        $this->dom->addChild(
            $this->ide,
            'cUF',
            $cUF,
            true,
            $identificador . 'Código da UF do emitente do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'cCT',
            $cCT,
            true,
            $identificador . 'Código numérico que compõe a Chave de Acesso'
        );
        $this->dom->addChild(
            $this->ide,
            'CFOP',
            $CFOP,
            true,
            $identificador . 'Código Fiscal de Operações e Prestações'
        );
        $this->dom->addChild(
            $this->ide,
            'natOp',
            $natOp,
            true,
            $identificador . 'Natureza da Operação'
        );
        $this->dom->addChild(
            $this->ide,
            'mod',
            $mod,
            true,
            $identificador . 'Modelo do documento fiscal'
        );
        $this->mod = $mod;
        $this->dom->addChild(
            $this->ide,
            'serie',
            $serie,
            true,
            $identificador . 'Série do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'nCT',
            $nCT,
            true,
            $identificador . 'Número do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'dhEmi',
            $dhEmi,
            true,
            $identificador . 'Data e hora de emissão do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'tpImp',
            $tpImp,
            true,
            $identificador . 'Formato de impressão do DACTE'
        );
        $this->dom->addChild(
            $this->ide,
            'tpEmis',
            $tpEmis,
            true,
            $identificador . 'Forma de emissão do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'cDV',
            $cDV,
            true,
            $identificador . 'Digito Verificador da chave de acesso do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'tpAmb',
            $tpAmb,
            true,
            $identificador . 'Tipo do Ambiente'
        );
        $this->dom->addChild(
            $this->ide,
            'tpCTe',
            $tpCTe,
            true,
            $identificador . 'Tipo do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'procEmi',
            $procEmi,
            true,
            $identificador . 'Identificador do processo de emissão do CT-e'
        );
        $this->dom->addChild(
            $this->ide,
            'verProc',
            $verProc,
            true,
            $identificador . 'Versão do processo de emissão'
        );
        $this->dom->addChild(
            $this->ide,
            'refCTE',
            $refCTE,
            false,
            $identificador . 'Chave de acesso do CT-e referenciado'
        );
        $this->dom->addChild(
            $this->ide,
            'cMunEnv',
            $cMunEnv,
            true,
            $identificador . 'Código do Município de envio do CT-e (de onde o documento foi transmitido)'
        );
        $this->dom->addChild(
            $this->ide,
            'xMunEnv',
            $xMunEnv,
            true,
            $identificador . 'Nome do Município de envio do CT-e (de onde o documento foi transmitido)'
        );
        $this->dom->addChild(
            $this->ide,
            'UFEnv',
            $UFEnv,
            true,
            $identificador . 'Sigla da UF de envio do CT-e (de onde o documento foi transmitido)'
        );
        $this->dom->addChild(
            $this->ide,
            'modal',
            $modal,
            true,
            $identificador . 'Modal'
        );
        $this->modal = $modal;
        $this->dom->addChild(
            $this->ide,
            'tpServ',
            $tpServ,
            true,
            $identificador . 'Tipo do Serviço'
        );
        $this->dom->addChild(
            $this->ide,
            'cMunIni',
            $cMunIni,
            true,
            $identificador . 'Nome do Município do início da prestação'
        );
        $this->dom->addChild(
            $this->ide,
            'xMunIni',
            $xMunIni,
            true,
            $identificador . 'Nome do Município do início da prestação'
        );
        $this->dom->addChild(
            $this->ide,
            'UFIni',
            $UFIni,
            true,
            $identificador . 'UF do início da prestação'
        );
        $this->dom->addChild(
            $this->ide,
            'cMunFim',
            $cMunFim,
            true,
            $identificador . 'Código do Município de término da prestação'
        );
        $this->dom->addChild(
            $this->ide,
            'xMunFim',
            $xMunFim,
            true,
            $identificador . 'Nome do Município do término da prestação'
        );
        $this->dom->addChild(
            $this->ide,
            'UFFim',
            $UFFim,
            true,
            $identificador . 'UF do término da prestação'
        );
        $this->dom->addChild(
            $this->ide,
            'retira',
            $retira,
            true,
            $identificador . 'Indicador se o Recebedor retira no Aeroporto, Filial, Porto ou Estação de Destino'
        );
        $this->dom->addChild(
            $this->ide,
            'xDetRetira',
            $xDetRetira,
            false,
            $identificador . 'Detalhes do retira'
        );
        $this->dom->addChild(
            $this->ide,
            'dhCont',
            $dhCont,
            false,
            $identificador . 'Data e Hora da entrada em contingência'
        );
        $this->dom->addChild(
            $this->ide,
            'xJust',
            $xJust,
            false,
            $identificador . 'Justificativa da entrada em contingência'
        );
        $this->tpServ = $tpServ;
        return $this->ide;
    }

    /**
     * Gera as tags para o elemento: toma03 (Indicador do "papel" do tomador do serviço no CT-e) e adiciona ao grupo ide
     * #35
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "toma03" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $toma Tomador do Serviço
     *
     * @return \NFePHP\Common\Dom\Dom
     */
    public function toma03Tag($toma = '')
    {
        $identificador = '#35 <toma03> - ';
        $this->toma03 = $this->dom->createElement('toma03');
        $this->dom->addChild(
            $this->toma03,
            'toma',
            $toma,
            true,
            $identificador . 'Tomador do Serviço'
        );
        return $this->toma03;
    }

    /**
     * Gera as tags para o elemento: toma3 (Indicador do "papel" do tomador do serviço no CT-e) e adiciona ao grupo ide
     * #37
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "toma4" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $toma  Tomador do Serviço
     * @param string $CNPJ  Número do CNPJ
     * @param string $CPF   Número do CPF
     * @param string $IE    Inscrição Estadual
     * @param string $xNome Razão Social ou Nome
     * @param string $xFant Nome Fantasia
     * @param string $fone  Telefone
     * @param string $email Endereço de email
     *
     * @return \NFePHP\Common\Dom\Dom
     */
    public function toma4Tag(
        $toma = '',
        $CNPJ = '',
        $CPF = '',
        $IE = '',
        $xNome = '',
        $xFant = '',
        $fone = '',
        $email = ''
    ) {
        $identificador = '#37 <toma4> - ';
        $this->toma4 = $this->dom->createElement('toma4');
        $this->dom->addChild(
            $this->toma4,
            'toma',
            $toma,
            true,
            $identificador . 'Tomador do Serviço'
        );
        if ($CNPJ != '') {
            $this->dom->addChild(
                $this->toma4,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
        } elseif ($CPF != '') {
            $this->dom->addChild(
                $this->toma4,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        } else {
            $this->dom->addChild(
                $this->toma4,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
            $this->dom->addChild(
                $this->toma4,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        }
        $this->dom->addChild(
            $this->toma4,
            'IE',
            $IE,
            false,
            $identificador . 'Inscrição Estadual'
        );
        $this->dom->addChild(
            $this->toma4,
            'xNome',
            $xNome,
            true,
            $identificador . 'Razão Social ou Nome'
        );
        $this->dom->addChild(
            $this->toma4,
            'xFant',
            $xFant,
            false,
            $identificador . 'Nome Fantasia'
        );
        $this->dom->addChild(
            $this->toma4,
            'fone',
            $fone,
            false,
            $identificador . 'Telefone'
        );
        $this->dom->addChild(
            $this->toma4,
            'email',
            $email,
            false,
            $identificador . 'Endereço de email'
        );
        return $this->toma4;
    }

    /**
     * Gera as tags para o elemento: "enderToma" (Dados do endereço) e adiciona ao grupo "toma4"
     * #45
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "enderToma" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $xLgr    Logradouro
     * @param string $nro     Número
     * @param string $xCpl    Complemento
     * @param string $xBairro Bairro
     * @param string $cMun    Código do município (utilizar a tabela do IBGE)
     * @param string $xMun    Nome do município
     * @param string $CEP     CEP
     * @param string $UF      Sigla da UF
     * @param string $cPais   Código do país
     * @param string $xPais   Nome do país
     *
     * @return \NFePHP\Common\Dom\Dom
     */
    public function enderTomaTag(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $CEP = '',
        $UF = '',
        $cPais = '',
        $xPais = ''
    ) {
        $identificador = '#45 <enderToma> - ';
        $this->enderToma = $this->dom->createElement('enderToma');
        $this->dom->addChild(
            $this->enderToma,
            'xLgr',
            $xLgr,
            true,
            $identificador . 'Logradouro'
        );
        $this->dom->addChild(
            $this->enderToma,
            'nro',
            $nro,
            true,
            $identificador . 'Número'
        );
        $this->dom->addChild(
            $this->enderToma,
            'xCpl',
            $xCpl,
            false,
            $identificador . 'Complemento'
        );
        $this->dom->addChild(
            $this->enderToma,
            'xBairro',
            $xBairro,
            true,
            $identificador . 'Bairro'
        );
        $this->dom->addChild(
            $this->enderToma,
            'cMun',
            $cMun,
            true,
            $identificador . 'Código do município (utilizar a tabela do IBGE)'
        );
        $this->dom->addChild(
            $this->enderToma,
            'xMun',
            $xMun,
            true,
            $identificador . 'Nome do município'
        );
        $this->dom->addChild(
            $this->enderToma,
            'CEP',
            $CEP,
            false,
            $identificador . 'CEP'
        );
        $this->dom->addChild(
            $this->enderToma,
            'UF',
            $UF,
            true,
            $identificador . 'Sigla da UF'
        );
        $this->dom->addChild(
            $this->enderToma,
            'cPais',
            $cPais,
            false,
            $identificador . 'Código do país'
        );
        $this->dom->addChild(
            $this->enderToma,
            'xPais',
            $xPais,
            false,
            $identificador . 'Nome do país'
        );
        return $this->enderToma;
    }

    /**
     * Gera as tags para o elemento: "compl" (Dados complementares do CT-e para fins operacionais ou comerciais)
     * #59
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "compl" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $xCaracAd  Característica adicional do transporte
     * @param string $xCaracSer Característica adicional do serviço
     * @param string $xEmi      Funcionário emissor do CTe
     * @param string $origCalc  Município de origem para efeito de cálculo do frete
     * @param string $destCalc  Município de destino para efeito de cálculo do frete
     * @param string $xObs      Observações Gerais
     *
     * @return \NFePHP\Common\Dom\Dom
     */
    public function complTag($xCaracAd = '', $xCaracSer = '', $xEmi = '', $origCalc = '', $destCalc = '', $xObs = '')
    {
        $identificador = '#59 <compl> - ';
        $this->compl = $this->dom->createElement('compl');
        $this->dom->addChild(
            $this->compl,
            'xCaracAd',
            $xCaracAd,
            false,
            $identificador . 'Característica adicional do transporte'
        );
        $this->dom->addChild(
            $this->compl,
            'xCaracSer',
            $xCaracSer,
            false,
            $identificador . 'Característica adicional do serviço'
        );
        $this->dom->addChild(
            $this->compl,
            'xEmi',
            $xEmi,
            false,
            $identificador . 'Funcionário emissor do CTe'
        );
        $this->dom->addChild(
            $this->compl,
            'origCalc',
            $origCalc,
            false,
            $identificador . 'Município de origem para efeito de cálculo do frete'
        );
        $this->dom->addChild(
            $this->compl,
            'destCalc',
            $destCalc,
            false,
            $identificador . 'Município de destino para efeito de cálculo do frete'
        );
        $this->dom->addChild(
            $this->compl,
            'xObs',
            $xObs,
            false,
            $identificador . 'Observações Gerais'
        );
        return $this->compl;
    }

    /**
     * Tag raiz do documento xml
     * Função chamada pelo método [ monta ]
     * @return DOMElement
     */
    private function zCTeTag() {
        if (empty($this->CTe)) {
            $this->CTe = $this->dom->createElement('CTe');
            $this->CTe->setAttribute('xmlns', 'http://www.portalfiscal.inf.br/cte');
        }
        return $this->CTe;
    }
}
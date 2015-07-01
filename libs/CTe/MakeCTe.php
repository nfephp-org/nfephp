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

use NFePHP\Common\Base\BaseMake;
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
    private $comp = array();
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
        $this->zCTeTag();
        if ($this->toma03 != '') {
            $this->dom->appChild($this->ide, $this->toma03, 'Falta tag "ide"');
        } else {
            $this->dom->appChild($this->toma4, $this->enderToma, 'Falta tag "toma4"');
            $this->dom->appChild($this->ide, $this->toma4, 'Falta tag "ide"');
        }
        $this->dom->appChild($this->infCte, $this->ide, 'Falta tag "infCte"');
        if ($this->compl != '') {
            if ($this->fluxo != '') {
                foreach ($this->pass as $pass) {
                    $this->dom->appChild($this->fluxo, $pass, 'Falta tag "fluxo"');
                }
                $this->dom->appChild($this->compl, $this->fluxo, 'Falta tag "infCte"');
            }
            if ($this->semData != '') {
                $this->zEntregaTag();
                $this->dom->appChild($this->entrega, $this->semData, 'Falta tag "Entrega"');
            } else if ($this->comData != '') {
                $this->zEntregaTag();
                $this->dom->appChild($this->entrega, $this->comData, 'Falta tag "Entrega"');
            } else if ($this->noPeriodo != '') {
                $this->zEntregaTag();
                $this->dom->appChild($this->entrega, $this->noPeriodo, 'Falta tag "Entrega"');
            } else if ($this->semHora != '') {
                $this->zEntregaTag();
                $this->dom->appChild($this->entrega, $this->semHora, 'Falta tag "Entrega"');
            } else if ($this->comHora != '') {
                $this->zEntregaTag();
                $this->dom->appChild($this->entrega, $this->comHora, 'Falta tag "Entrega"');
            } else if ($this->noInter != '') {
                $this->zEntregaTag();
                $this->dom->appChild($this->entrega, $this->noInter, 'Falta tag "Entrega"');
            }
            foreach ($this->obsCont as $obsCont) {
                $this->dom->appChild($this->compl, $obsCont, 'Falta tag "compl"');
            }
            foreach ($this->obsFisco as $obsFisco) {
                $this->dom->appChild($this->compl, $obsFisco, 'Falta tag "compl"');
            }
            $this->dom->appChild($this->infCte, $this->compl, 'Falta tag "infCte"');
        }
        $this->dom->appChild($this->emit, $this->enderEmit, 'Falta tag "emit"');
        $this->dom->appChild($this->infCte, $this->emit, 'Falta tag "infCte"');
        if ($this->rem != '') {
            $this->dom->appChild($this->rem, $this->enderReme, 'Falta tag "rem"');
            $this->dom->appChild($this->infCte, $this->rem, 'Falta tag "infCte"');
        }
        if ($this->exped != '') {
            $this->dom->appChild($this->exped, $this->enderExped, 'Falta tag "exped"');
            $this->dom->appChild($this->infCte, $this->exped, 'Falta tag "infCte"');
        }
        if ($this->receb != '') {
            $this->dom->appChild($this->receb, $this->enderReceb, 'Falta tag "receb"');
            $this->dom->appChild($this->infCte, $this->receb, 'Falta tag "infCte"');
        }
        if ($this->dest != '') {
            $this->dom->appChild($this->dest, $this->enderDest, 'Falta tag "dest"');
            $this->dom->appChild($this->infCte, $this->dest, 'Falta tag "infCte"');
        }
        foreach ($this->comp as $comp) {
            $this->dom->appChild($this->vPrest, $comp, 'Falta tag "vPrest"');
        }
        $this->dom->appChild($this->infCte, $this->vPrest, 'Falta tag "infCte"');
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
     * @return \DOMElement
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
     * @return \DOMElement
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
     * Gera as tags para o elemento: toma4 (Indicador do "papel" do tomador do serviço no CT-e) e adiciona ao grupo ide
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
     * @return \DOMElement
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
     * @return \DOMElement
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
     * @return \DOMElement
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
     * Gera as tags para o elemento: "fluxo" (Previsão do fluxo da carga)
     * #63
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "fluxo" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $xOrig Sigla ou código interno da Filial/Porto/Estação/ Aeroporto de Origem
     * @param string $xDest Sigla ou código interno da Filial/Porto/Estação/Aeroporto de Destino
     * @param string $xRota Código da Rota de Entrega
     *
     * @return \DOMElement
     */
    public function fluxoTag($xOrig = '', $xDest = '', $xRota = '')
    {
        $identificador = '#63 <fluxo> - ';
        $this->fluxo = $this->dom->createElement('fluxo');
        $this->dom->addChild(
            $this->fluxo,
            'xOrig',
            $xOrig,
            false,
            $identificador . 'Sigla ou código interno da Filial/Porto/Estação/ Aeroporto de Origem'
        );
        $this->dom->addChild(
            $this->fluxo,
            'xDest',
            $xDest,
            false,
            $identificador . 'Sigla ou código interno da Filial/Porto/Estação/Aeroporto de Destino'
        );
        $this->dom->addChild(
            $this->fluxo,
            'xRota',
            $xRota,
            false,
            $identificador . 'Código da Rota de Entrega'
        );
        return $this->fluxo;
    }

    /**
     * Gera as tags para o elemento: "pass"
     * #65
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "pass" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $xPass Sigla ou código interno da Filial/Porto/Estação/Aeroporto de Passagem
     *
     * @return \DOMElement
     */
    public function passTag($xPass = '')
    {
        $identificador = '#65 <pass> - ';
        $this->pass[] = $this->dom->createElement('pass');
        $posicao = (integer) count($this->pass) - 1;
        $this->dom->addChild(
            $this->pass[$posicao],
            'xPass',
            $xPass,
            false,
            $identificador . 'Sigla ou código interno da Filial/Porto/Estação/Aeroporto de Passagem'
        );
        return $this->pass[$posicao];
    }

    /**
     * Gera as tags para o elemento: "semData" (Entrega sem data definida)
     * #70
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "semData" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $tpPer Tipo de data/período programado para entrega
     *
     * @return \DOMElement
     */
    public function semDataTag($tpPer = '')
    {
        $identificador = '#70 <semData> - ';
        $this->semData = $this->dom->createElement('semData');
        $this->dom->addChild(
            $this->semData,
            'tpPer',
            $tpPer,
            true,
            $identificador . 'Tipo de data/período programado para entrega'
        );
        return $this->semData;
    }

    /**
     * Gera as tags para o elemento: "comData" (Entrega com data definida)
     * #72
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "comData" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $tpPer Tipo de data/período programado para entrega
     * @param string $dProg Data programada
     *
     * @return \DOMElement
     */
    public function comDataTag($tpPer = '', $dProg = '')
    {
        $identificador = '#72 <comData> - ';
        $this->comData = $this->dom->createElement('comData');
        $this->dom->addChild(
            $this->comData,
            'tpPer',
            $tpPer,
            true,
            $identificador . 'Tipo de data/período programado para entrega'
        );
        $this->dom->addChild(
            $this->comData,
            'dProg',
            $dProg,
            true,
            $identificador . 'Data programada'
        );
        return $this->comData;
    }

    /**
     * Gera as tags para o elemento: "noPeriodo" (Entrega no período definido)
     * #75
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "noPeriodo" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $tpPer Tipo de data/período programado para entrega
     * @param string $dIni  Data inicial
     * @param string $dFim  Data final
     *
     * @return \DOMElement
     */
    public function noPeriodoTag($tpPer = '', $dIni = '', $dFim = '')
    {
        $identificador = '#75 <noPeriodo> - ';
        $this->noPeriodo = $this->dom->createElement('noPeriodo');
        $this->dom->addChild(
            $this->noPeriodo,
            'tpPer',
            $tpPer,
            true,
            $identificador . 'Tipo de data/período programado para entrega'
        );
        $this->dom->addChild(
            $this->noPeriodo,
            'dIni',
            $dIni,
            true,
            $identificador . 'Data inicial'
        );
        $this->dom->addChild(
            $this->noPeriodo,
            'dFim',
            $dFim,
            true,
            $identificador . 'Data final'
        );
        return $this->noPeriodo;
    }

    /**
     * Gera as tags para o elemento: "semHora" (Entrega sem hora definida)
     * #79
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "semHora" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $tpHor Tipo de hora
     *
     * @return \DOMElement
     */
    public function semHoraTag($tpHor = '')
    {
        $identificador = '#79 <semHora> - ';
        $this->semHora = $this->dom->createElement('semHora');
        $this->dom->addChild(
            $this->semHora,
            'tpHor',
            $tpHor,
            true,
            $identificador . 'Tipo de hora'
        );
        return $this->semHora;
    }

    /**
     * Gera as tags para o elemento: "comHora" (Entrega sem hora definida)
     * # = 81
     * Nível = 3
     * Os parâmetros para esta função são todos os elementos da tag "comHora" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $tpHor Tipo de hora
     * @param string $hProg Hora programada
     *
     * @return \DOMElement
     */
    public function comHoraTag($tpHor = '', $hProg = '')
    {
        $identificador = '#81 <comHora> - ';
        $this->comHora = $this->dom->createElement('comHora');
        $this->dom->addChild(
            $this->comHora,
            'tpHor',
            $tpHor,
            true,
            $identificador . 'Tipo de hora'
        );
        $this->dom->addChild(
            $this->comHora,
            'hProg',
            $hProg,
            true,
            $identificador . 'Hora programada'
        );
        return $this->comHora;
    }

    /**
     * Gera as tags para o elemento: "noInter" (Entrega no intervalo de horário definido)
     * #84
     * Nível: 3
     * Os parâmetros para esta função são todos os elementos da tag "noInter" do tipo elemento (Ele = E|CE|A) e nível 4
     *
     * @param string $tpHor Tipo de hora
     * @param string $hIni  Hora inicial
     * @param string $hFim  Hora final
     *
     * @return \DOMElement
     */
    public function noInterTag($tpHor = '', $hIni = '', $hFim = '')
    {
        $identificador = '#84 <noInter> - ';
        $this->noInter = $this->dom->createElement('noInter');
        $this->dom->addChild(
            $this->noInter,
            'tpHor',
            $tpHor,
            true,
            $identificador . 'Tipo de hora'
        );
        $this->dom->addChild(
            $this->noInter,
            'hIni',
            $hIni,
            true,
            $identificador . 'Hora inicial'
        );
        $this->dom->addChild(
            $this->noInter,
            'hFim',
            $hFim,
            true,
            $identificador . 'Hora final'
        );
        return $this->noInter;
    }

    /**
     * Gera as tags para o elemento: "ObsCont" (Campo de uso livre do contribuinte)
     * #91
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "ObsCont" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $xCampo Identificação do campo
     * @param string $xTexto Conteúdo do campo
     *
     * @return boolean
     */
    public function obsContTag($xCampo = '', $xTexto = '')
    {
        $identificador = '#91 <ObsCont> - ';
        $posicao = (integer) count($this->obsCont) - 1;
        if (count($this->obsCont) <= 10) {
            $this->obsCont[] = $this->dom->createElement('ObsCont');
            $this->obsCont[$posicao]->setAttribute('xCampo', $xCampo);
            $this->dom->addChild(
                $this->obsCont[$posicao],
                'xTexto',
                $xTexto,
                true,
                $identificador . 'Conteúdo do campo'
            );
            return true;
        }
        $this->erros[] = array(
            'tag' => (string) '<ObsCont>',
            'desc' => (string) 'Campo de uso livre do contribuinte',
            'erro' => (string) 'Tag deve aparecer de 0 a 10 vezes'
        );
        return false;
    }

    /**
     * Gera as tags para o elemento: "ObsFisco" (Campo de uso livre do contribuinte)
     * #94
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "ObsFisco" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $xCampo Identificação do campo
     * @param string $xTexto Conteúdo do campo
     *
     * @return boolean
     */
    public function obsFiscoTag($xCampo = '', $xTexto = '')
    {
        $identificador = '#94 <ObsFisco> - ';
        $posicao = (integer) count($this->obsFisco) - 1;
        if (count($this->obsFisco) <= 10) {
            $this->obsFisco[] = $this->dom->createElement('obsFisco');
            $this->obsFisco[$posicao]->setAttribute('xCampo', $xCampo);
            $this->dom->addChild(
                $this->obsFisco[$posicao],
                'xTexto',
                $xTexto,
                true,
                $identificador . 'Conteúdo do campo'
            );
            return true;
        }
        $this->erros[] = array(
            'tag' => (string) '<ObsFisco>',
            'desc' => (string) 'Campo de uso livre do contribuinte',
            'erro' => (string) 'Tag deve aparecer de 0 a 10 vezes'
        );
        return false;
    }

    /**
     * Gera as tags para o elemento: "emit" (Identificação do Emitente do CT-e)
     * #97
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "emit" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $CNPJ  CNPJ do emitente
     * @param string $IE    Inscrição Estadual do Emitente
     * @param string $xNome Razão social ou Nome do emitente
     * @param string $xFant Nome fantasia
     *
     * @return \DOMElement
     */
    public function emitTag($CNPJ = '', $IE = '', $xNome = '', $xFant = '')
    {
        $identificador = '#97 <emit> - ';
        $this->emit = $this->dom->createElement('emit');
        $this->dom->addChild(
            $this->emit,
            'CNPJ',
            $CNPJ,
            true,
            $identificador . 'CNPJ do emitente'
        );
        $this->dom->addChild(
            $this->emit,
            'IE',
            $IE,
            true,
            $identificador . 'Inscrição Estadual do Emitente'
        );
        $this->dom->addChild(
            $this->emit,
            'xNome',
            $xNome,
            true,
            $identificador . 'Razão social ou Nome do emitente'
        );
        $this->dom->addChild(
            $this->emit,
            'xFant',
            $xFant,
            true,
            $identificador . 'Nome fantasia'
        );
        return $this->emit;
    }

    /**
     * Gera as tags para o elemento: "enderEmit" (Endereço do emitente)
     * #102
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "enderEmit" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $xLgr    Logradouro
     * @param string $nro     Número
     * @param string $xCpl    Complemento
     * @param string $xBairro Bairro
     * @param string $cMun    Código do município (utilizar a tabela do IBGE)
     * @param string $xMun    Nome do município
     * @param string $CEP     CEP
     * @param string $UF      Sigla da UF
     * @param string $fone    Telefone
     *
     * @return \DOMElement
     */
    public function enderEmitTag(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $CEP = '',
        $UF = '',
        $fone = ''
    ) {
        $identificador = '#102 <enderEmit> - ';
        $this->enderEmit = $this->dom->createElement('enderEmit');
        $this->dom->addChild(
            $this->enderEmit,
            'xLgr',
            $xLgr,
            true,
            $identificador . 'Logradouro'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'nro',
            $nro,
            true,
            $identificador . 'Número'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'xCpl',
            $xCpl,
            false,
            $identificador . 'Complemento'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'xBairro',
            $xBairro,
            true,
            $identificador . 'Bairro'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'cMun',
            $cMun,
            true,
            $identificador . 'Código do município'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'xMun',
            $xMun,
            true,
            $identificador . 'Nome do município'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'CEP',
            $CEP,
            false,
            $identificador . 'CEP'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'UF',
            $UF,
            true,
            $identificador . 'Sigla da UF'
        );
        $this->dom->addChild(
            $this->enderEmit,
            'fone',
            $fone,
            false,
            $identificador . 'Telefone'
        );
        return $this->enderEmit;
    }

    /**
     * Gera as tags para o elemento: "rem" (Informações do Remetente das mercadorias transportadas pelo CT-e)
     * #112
     * Nível = 1
     * Os parâmetros para esta função são todos os elementos da tag "rem" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $CNPJ  Número do CNPJ
     * @param string $CPF   Número do CPF
     * @param string $IE    Inscrição Estadual
     * @param string $xNome Razão social ou nome do remetente
     * @param string $xFant Nome fantasia
     * @param string $fone  Telefone
     * @param string $email Endereço de email
     *
     * @return \DOMElement
     */
    public function remTag($CNPJ = '', $CPF = '', $IE = '', $xNome = '', $xFant = '', $fone = '', $email = '')
    {
        $identificador = '#97 <rem> - ';
        $this->rem = $this->dom->createElement('rem');
        if ($CNPJ != '') {
            $this->dom->addChild(
                $this->rem,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'CNPJ do Remente'
            );
        } else if ($CPF != '') {
            $this->dom->addChild(
                $this->rem,
                'CPF',
                $CPF,
                true,
                $identificador . 'CPF do Remente'
            );
        } else {
            $this->dom->addChild(
                $this->rem,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'CNPJ do Remente'
            );
            $this->dom->addChild(
                $this->rem,
                'CPF',
                $CPF,
                true,
                $identificador . 'CPF do remente'
            );
        }
        $this->dom->addChild(
            $this->rem,
            'IE',
            $IE,
            true,
            $identificador . 'Inscrição Estadual do remente'
        );
        $this->dom->addChild(
            $this->rem,
            'xNome',
            $xNome,
            true,
            $identificador . 'Razão social ou Nome do remente'
        );
        $this->dom->addChild(
            $this->rem,
            'xFant',
            $xFant,
            true,
            $identificador . 'Nome fantasia'
        );
        $this->dom->addChild(
            $this->rem,
            'fone',
            $fone,
            false,
            $identificador . 'Telefone'
        );
        $this->dom->addChild(
            $this->rem,
            'email',
            $email,
            false,
            $identificador . 'Endereço de email'
        );
        return $this->rem;
    }

    /**
     * Gera as tags para o elemento: "enderReme" (Dados do endereço)
     * #119
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "enderReme" do tipo elemento (Ele = E|CE|A) e nível 3
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
     * @return \DOMElement
     */
    public function enderRemeTag(
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
        $identificador = '#119 <enderReme> - ';
        $this->enderReme = $this->dom->createElement('enderReme');
        $this->dom->addChild(
            $this->enderReme,
            'xLgr',
            $xLgr,
            true,
            $identificador . 'Logradouro'
        );
        $this->dom->addChild(
            $this->enderReme,
            'nro',
            $nro,
            true,
            $identificador . 'Número'
        );
        $this->dom->addChild(
            $this->enderReme,
            'xCpl',
            $xCpl,
            false,
            $identificador . 'Complemento'
        );
        $this->dom->addChild(
            $this->enderReme,
            'xBairro',
            $xBairro,
            true,
            $identificador . 'Bairro'
        );
        $this->dom->addChild(
            $this->enderReme,
            'cMun',
            $cMun,
            true,
            $identificador . 'Código do município (utilizar a tabela do IBGE)'
        );
        $this->dom->addChild(
            $this->enderReme,
            'xMun',
            $xMun,
            true,
            $identificador . 'Nome do município'
        );
        $this->dom->addChild(
            $this->enderReme,
            'CEP',
            $CEP,
            false,
            $identificador . 'CEP'
        );
        $this->dom->addChild(
            $this->enderReme,
            'UF',
            $UF,
            true,
            $identificador . 'Sigla da UF'
        );
        $this->dom->addChild(
            $this->enderReme,
            'cPais',
            $cPais,
            false,
            $identificador . 'Código do país'
        );
        $this->dom->addChild(
            $this->enderReme,
            'xPais',
            $xPais,
            false,
            $identificador . 'Nome do país'
        );
        return $this->enderReme;
    }

    /**
     * Gera as tags para o elemento: "exped" (Informações do Expedidor da Carga)
     * #142
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "exped" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $CNPJ  Número do CNPJ
     * @param string $CPF   Número do CPF
     * @param string $IE    Inscrição Estadual
     * @param string $xNome Razão Social ou Nome
     * @param string $fone  Telefone
     * @param string $email Endereço de email
     *
     * @return \DOMElement
     */
    public function expedTag($CNPJ = '', $CPF = '', $IE = '', $xNome = '', $fone = '', $email = '')
    {
        $identificador = '#142 <exped> - ';
        $this->exped = $this->dom->createElement('exped');
        if ($CNPJ != '') {
            $this->dom->addChild(
                $this->exped,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
        } else if ($CPF != '') {
            $this->dom->addChild(
                $this->exped,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        } else {
            $this->dom->addChild(
                $this->exped,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
            $this->dom->addChild(
                $this->exped,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        }
        $this->dom->addChild(
            $this->exped,
            'IE',
            $IE,
            true,
            $identificador . 'Inscrição Estadual'
        );
        $this->dom->addChild(
            $this->exped,
            'xNome',
            $xNome,
            true,
            $identificador . 'Razão social ou Nome'
        );
        $this->dom->addChild(
            $this->exped,
            'fone',
            $fone,
            false,
            $identificador . 'Telefone'
        );
        $this->dom->addChild(
            $this->exped,
            'email',
            $email,
            false,
            $identificador . 'Endereço de email'
        );
        return $this->exped;
    }

    /**
     * Gera as tags para o elemento: "enderExped" (Dados do endereço)
     * #148
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "enderExped" do tipo elemento (Ele = E|CE|A) e nível 3
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
     * @return \DOMElement
     */
    public function enderExpedTag(
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
        $identificador = '#148 <enderExped> - ';
        $this->enderExped = $this->dom->createElement('enderExped');
        $this->dom->addChild(
            $this->enderExped,
            'xLgr',
            $xLgr,
            true,
            $identificador . 'Logradouro'
        );
        $this->dom->addChild(
            $this->enderExped,
            'nro',
            $nro,
            true,
            $identificador . 'Número'
        );
        $this->dom->addChild(
            $this->enderExped,
            'xCpl',
            $xCpl,
            false,
            $identificador . 'Complemento'
        );
        $this->dom->addChild(
            $this->enderExped,
            'xBairro',
            $xBairro,
            true,
            $identificador . 'Bairro'
        );
        $this->dom->addChild(
            $this->enderExped,
            'cMun',
            $cMun,
            true,
            $identificador . 'Código do município (utilizar a tabela do IBGE)'
        );
        $this->dom->addChild(
            $this->enderExped,
            'xMun',
            $xMun,
            true,
            $identificador . 'Nome do município'
        );
        $this->dom->addChild(
            $this->enderExped,
            'CEP',
            $CEP,
            false,
            $identificador . 'CEP'
        );
        $this->dom->addChild(
            $this->enderExped,
            'UF',
            $UF,
            true,
            $identificador . 'Sigla da UF'
        );
        $this->dom->addChild(
            $this->enderExped,
            'cPais',
            $cPais,
            false,
            $identificador . 'Código do país'
        );
        $this->dom->addChild(
            $this->enderExped,
            'xPais',
            $xPais,
            false,
            $identificador . 'Nome do país'
        );
        return $this->enderExped;
    }

    /**
     * Gera as tags para o elemento: "receb" (Informações do Recebedor da Carga)
     * #160
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "receb" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $CNPJ  Número do CNPJ
     * @param string $CPF   Número do CPF
     * @param string $IE    Inscrição Estadual
     * @param string $xNome Razão Social ou Nome
     * @param string $fone  Telefone
     * @param string $email Endereço de email
     *
     * @return \DOMElement
     */
    public function recebTag($CNPJ = '', $CPF = '', $IE = '', $xNome = '', $fone = '', $email = '')
    {
        $identificador = '#160 <receb> - ';
        $this->receb = $this->dom->createElement('receb');
        if ($CNPJ != '') {
            $this->dom->addChild(
                $this->receb,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
        } else if ($CPF != '') {
            $this->dom->addChild(
                $this->receb,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        } else {
            $this->dom->addChild(
                $this->receb,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
            $this->dom->addChild(
                $this->receb,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        }
        $this->dom->addChild(
            $this->receb,
            'IE',
            $IE,
            true,
            $identificador . 'Inscrição Estadual'
        );
        $this->dom->addChild(
            $this->receb,
            'xNome',
            $xNome,
            true,
            $identificador . 'Razão social ou Nome'
        );
        $this->dom->addChild(
            $this->receb,
            'fone',
            $fone,
            false,
            $identificador . 'Telefone'
        );
        $this->dom->addChild(
            $this->receb,
            'email',
            $email,
            false,
            $identificador . 'Endereço de email'
        );
        return $this->receb;
    }

    /**
     * Gera as tags para o elemento: "enderReceb" (Informações do Recebedor da Carga)
     * #166
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "enderReceb" do tipo elemento (Ele = E|CE|A) e nível 3
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
     * @return \DOMElement
     */
    public function enderRecebTag(
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
        $identificador = '#160 <enderReceb> - ';
        $this->enderReceb = $this->dom->createElement('enderReceb');
        $this->dom->addChild(
            $this->enderReceb,
            'xLgr',
            $xLgr,
            true,
            $identificador . 'Logradouro'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'nro',
            $nro,
            true,
            $identificador . 'Número'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'xCpl',
            $xCpl,
            false,
            $identificador . 'Complemento'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'xBairro',
            $xBairro,
            true,
            $identificador . 'Bairro'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'cMun',
            $cMun,
            true,
            $identificador . 'Código do município (utilizar a tabela do IBGE)'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'xMun',
            $xMun,
            true,
            $identificador . 'Nome do município'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'CEP',
            $CEP,
            false,
            $identificador . 'CEP'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'UF',
            $UF,
            true,
            $identificador . 'Sigla da UF'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'cPais',
            $cPais,
            false,
            $identificador . 'Código do país'
        );
        $this->dom->addChild(
            $this->enderReceb,
            'xPais',
            $xPais,
            false,
            $identificador . 'Nome do país'
        );
        return $this->enderReceb;
    }

    /**
     * Gera as tags para o elemento: "dest" (Informações do Destinatário do CT-e)
     * #178
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "dest" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $CNPJ  Número do CNPJ
     * @param string $CPF   Número do CPF
     * @param string $IE    Inscrição Estadual
     * @param string $xNome Razão Social ou Nome
     * @param string $fone  Telefone
     * @param string $ISUF  Inscrição na SUFRAMA
     * @param string $email Endereço de email
     *
     * @return \DOMElement
     */
    public function destTag($CNPJ = '', $CPF = '', $IE = '', $xNome = '', $fone = '', $ISUF = '', $email = '')
    {
        $identificador = '#178 <dest> - ';
        $this->dest = $this->dom->createElement('dest');
        if ($CNPJ != '') {
            $this->dom->addChild(
                $this->dest,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
        } else if ($CPF != '') {
            $this->dom->addChild(
                $this->dest,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        } else {
            $this->dom->addChild(
                $this->dest,
                'CNPJ',
                $CNPJ,
                true,
                $identificador . 'Número do CNPJ'
            );
            $this->dom->addChild(
                $this->dest,
                'CPF',
                $CPF,
                true,
                $identificador . 'Número do CPF'
            );
        }
        $this->dom->addChild(
            $this->dest,
            'IE',
            $IE,
            true,
            $identificador . 'Inscrição Estadual'
        );
        $this->dom->addChild(
            $this->dest,
            'xNome',
            $xNome,
            true,
            $identificador . 'Razão social ou Nome'
        );
        $this->dom->addChild(
            $this->dest,
            'fone',
            $fone,
            false,
            $identificador . 'Telefone'
        );
        $this->dom->addChild(
            $this->dest,
            'ISUF',
            $ISUF,
            false,
            $identificador . 'Inscrição na SUFRAMA'
        );
        $this->dom->addChild(
            $this->dest,
            'email',
            $email,
            false,
            $identificador . 'Endereço de email'
        );
        return $this->dest;
    }

    /**
     * Gera as tags para o elemento: "enderDest" (Informações do Recebedor da Carga)
     * # = 185
     * Nível = 2
     * Os parâmetros para esta função são todos os elementos da tag "enderDest" do tipo elemento (Ele = E|CE|A) e nível 3
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
     * @return \DOMElement
     */
    public function enderDestTag(
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
        $identificador = '#185 <enderDest> - ';
        $this->enderDest = $this->dom->createElement('enderDest');
        $this->dom->addChild(
            $this->enderDest,
            'xLgr',
            $xLgr,
            true,
            $identificador . 'Logradouro'
        );
        $this->dom->addChild(
            $this->enderDest,
            'nro',
            $nro,
            true,
            $identificador . 'Número'
        );
        $this->dom->addChild(
            $this->enderDest,
            'xCpl',
            $xCpl,
            false,
            $identificador . 'Complemento'
        );
        $this->dom->addChild(
            $this->enderDest,
            'xBairro',
            $xBairro,
            true,
            $identificador . 'Bairro'
        );
        $this->dom->addChild(
            $this->enderDest,
            'cMun',
            $cMun,
            true,
            $identificador . 'Código do município (utilizar a tabela do IBGE)'
        );
        $this->dom->addChild(
            $this->enderDest,
            'xMun',
            $xMun,
            true,
            $identificador . 'Nome do município'
        );
        $this->dom->addChild(
            $this->enderDest,
            'CEP',
            $CEP,
            false,
            $identificador . 'CEP'
        );
        $this->dom->addChild(
            $this->enderDest,
            'UF',
            $UF,
            true,
            $identificador . 'Sigla da UF'
        );
        $this->dom->addChild(
            $this->enderDest,
            'cPais',
            $cPais,
            false,
            $identificador . 'Código do país'
        );
        $this->dom->addChild(
            $this->enderDest,
            'xPais',
            $xPais,
            false,
            $identificador . 'Nome do país'
        );
        return $this->enderDest;
    }

    /**
     * Gera as tags para o elemento: "vPrest" (Local de Entrega constante na Nota Fiscal)
     * #208
     * Nível: 1
     * Os parâmetros para esta função são todos os elementos da tag "vPrest" do tipo elemento (Ele = E|CE|A) e nível 2
     *
     * @param string $vTPrest Valor Total da Prestação do Serviço
     * @param string $vRec    Valor a Receber
     *
     * @return \DOMElement
     */
    public function vPrestTag($vTPrest = '', $vRec = '')
    {
        $identificador = '#208 <vPrest> - ';
        $this->vPrest = $this->dom->createElement('vPrest');
        $this->dom->addChild(
            $this->vPrest,
            'vTPrest',
            $vTPrest,
            true,
            $identificador . 'Valor Total da Prestação do Serviço'
        );
        $this->dom->addChild(
            $this->vPrest,
            'vRec',
            $vRec,
            true,
            $identificador . 'Valor a Receber'
        );
        return $this->vPrest;
    }

    /**
     * Gera as tags para o elemento: "Comp" (Local de Entrega constante na Nota Fiscal)
     * #211
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "Comp" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @param string $xNome Nome do componente
     * @param string $vComp Valor do componente
     *
     * @return \DOMElement
     */
    public function compTag($xNome = '', $vComp = '')
    {
        $identificador = '#65 <pass> - ';
        $this->comp[] = $this->dom->createElement('Comp');
        $posicao = (integer) count($this->obsCont) - 1;
        $this->dom->addChild(
            $this->comp[$posicao],
            'xNome',
            $xNome,
            false,
            $identificador . 'Nome do componente'
        );
        $this->dom->addChild(
            $this->comp[$posicao],
            'vComp',
            $vComp,
            false,
            $identificador . 'Valor do componente'
        );
        return $this->comp[$posicao];
    }

    /**
     * Tag raiz do documento xml
     * Função chamada pelo método [ monta ]
     * @return \DOMElement
     */
    private function zCTeTag() {
        if (empty($this->CTe)) {
            $this->CTe = $this->dom->createElement('CTe');
            $this->CTe->setAttribute('xmlns', 'http://www.portalfiscal.inf.br/cte');
        }
        return $this->CTe;
    }

    /**
     * Gera as tags para o elemento: "Entrega" (Informações ref. a previsão de entrega)
     * #69
     * Nível: 2
     * Os parâmetros para esta função são todos os elementos da tag "Entrega" do tipo elemento (Ele = E|CE|A) e nível 3
     *
     * @return \DOMElement
     */
    private function zEntregaTag()
    {
        $this->entrega = $this->dom->createElement('Entrega');
        return $this->entrega;
    }
}
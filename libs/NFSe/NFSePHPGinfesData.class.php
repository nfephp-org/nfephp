<?php
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
 *
 * @package   NFePHP
 * @name      NFSeSEGinfesData
 * @version   0.0.1
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Hugo Cegana <cegana at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *            Roberto Leite Machado <linux dot rlm at gamil dot com>
 * 
 */

class NFSePHPGinfesData {

    // número do rps (recibo provisório de serviços) que identificará a NFS-e.
    private $numrps = "";
    // série da nota fiscal
    private $numSerie = '1';
    // 1 = nota conjugada / 2-mista / 3-cupom
    private $tipo = '1';
    /*
      01 – Tributação no municipio;
      02 – Tributação fora do municipio;
      03 – Isenção;
      04 – Imune;
      05 – Exigibilidade suspensa por decisão judicial;
      06 – Exigibilidade suspensa por procedimento administrativo.
     */
    private $natOperacao = '1';
    // 1 = sim | 2 = não
    private $optanteSimplesNacional = '1';
    // 1 = sim | 2 = não
    private $incentivadorCultural = '2';

    /*
      Código de identificação do regime especial de tributação
      1 – Microempresa municipal
      2 - Estimativa
      3 – Sociedade de profissionais
      4 – Cooperativa
      5 - Microempresário Individual (MEI)
      6 - Microempresário e Empresa de Pequeno Porte(ME EPP)
     */
    private $regimeEspecialTributacao = '6';
    // 1 - normal 2 - cancelado (status da nota fiscal)
    private $status = '1';
    // código do municipio do prestador segundo tabela do ibge
    private $cMun = '3525904';

    /**
     * 
     * @var array itens {valor, valorDeducoes, valorPis, valorCofins, valorIr, valorCsll, issRetido,valorIss,valorIssRetido,outrasRetencoes, baseCalculo,aliquota,valorLiquidoNfse,descontoIncondicionado,descontoCondicionado,itemListaServico,codigoCnae,discriminacao}
     */
    private $Item =
            array(
        'valorServicos' => 0,
        'valorDeducoes' => 0,
        'valorPis' => 0,
        'valorCofins' => 0,
        'valorInss' => 0,
        'valorIr' => 0,
        'valorCsll' => 0,
        'issRetido' => 1, // 1=sim | 2=nao
        'valorIss' => 0,
        'valorIssRetido' => 0,
        'outrasRetencoes' => 0,
        'baseCalculo' => 0,
        'aliquota' => 0, // percentual calculado Ex: 2% informar 0.02
        'descontoIncondicionado' => 0,
        'descontoCondicionado' => 0,
        'itemListaServico' => '1.03',
        'codigoTributacaoMunicipio' => '1.03.01 / 670',
        'discriminacao' => "LICENCA DE USO DE SOFTWARE"
    );

    /*     *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *
     * OBS: 
     * No item, os campos 'itemListaServico'  e 'codigoTributacaoMunicipio' precisam ter o EXATO formato conforme cadastro na prefeitura do municipio         
     * 
     * Para verificar qual o código de tributação referente ao serviço informado no arquivo acesse o Ginfes: http://PREFEITURADASUACIDADE.ginfes.com.br 
     * com o usuário e senha da empresa. 
     *
     * Clique em emitir NFS-e / clique em serviços prestados / clique na lupa ao lado de Código do Serviço/Atividade: informe o código ou a 
     * descrição do serviço na barra de pesquisa e clique em pesquisar / será exibido uma lista com todos os serviços referente ao código / 
     * descrição pesquisado, o código de tributação é a coluna código de atividade copie exatamente como demonstrado no sistema.
     * 
     * *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  * */
    /**
     *
     * DADOS DO TOMADOR
     * 
     */
    private $tomaCPF = '';
    private $tomaCNPJ = '';
    private $tomaRazaoSocial = '';
    private $tomaEndLogradouro = '';
    private $tomaEndNumero = '';
    private $tomaEndComplemento = '';
    private $tomaEndBairro = '';
    private $tomaEndxMun = '';
    private $tomaEndcMun = '';
    private $tomaEndUF = '';
    private $tomaEndCep = '';
    private $tomaEmail = '';
    
    /* -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+- FIM esta parte toda vai para a aplicação +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+- */

    /**
     * 
     * @param string $rps
     */
    public function __construct($rps) {
        $this->numrps = date("ym") . sprintf('%011s', $rps);
    }

    /**
     * 
     * @param string $campo
     * @param mixed $valor
     */
    public function set($campo, $valor) {
        $this->{$campo} = $valor;
    }

    /**
     * 
     * @param string $campo
     * @return array
     */
    public function get($campo) {
        return $this->{$campo};
    }

    /**
     * 
     * @param string $campo
     * @param string $valor
     */
    public function setItem($campo, $valor) {
        $this->Item[$campo] = $valor;
        $this->Item['baseCalculo']      = $this->Item['valorServicos'] - $this->Item['descontoIncondicionado'] - $this->Item['valorDeducoes'] ;
        $this->Item['valorLiquidoNfse'] = $this->Item['valorServicos'] - $this->Item['valorPis'] - $this->Item['valorCofins'] - $this->Item['valorInss'] - $this->Item['valorCsll'] - $this->Item['outrasRetencoes'] - $this->Item['valorIss'] - $this->Item['descontoIncondicionado'] - $this->Item['descontoCondicionado'];
    }
    
    /**
     * 
     * @return array
     */
    public function getArrayItem(){
        return $this->Item;
    }

    /**
     * 
     * @param string $campo
     * @return array
     */
    public function getItem($campo) {
        return $this->Item[$campo];
    }

    /**
     * 
     * @param type $razao
     * @param type $fantasia
     * @param type $cnpj
     * @param type $im
     */
    public function setEmitente($razao, $fantasia, $cnpj, $im) {
        $this->razaoSocial = $razao;
        $this->nomeFantasia = $fantasia;
        $this->CNPJ = $cnpj;
        $this->IM = $im;
    }
}

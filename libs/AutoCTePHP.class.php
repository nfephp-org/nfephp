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
 * Esta classe extende a classe ToolsNFePHP e contêm os métodos "Auto" removidos 
 * da classe principal da API
 *
 * @package   NFePHP
 * @name      AutoCTeNFePHP
 * @version   1.0.1
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *          Lucimar A. Magalhaes <lucimar.magalhaes at assistsolucoes dot com dot br>
 * 
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
//carrega a classe principal da API 
require_once('CTeNFePHP.class.php');
//carrega a classe de impressao da DANFE
require_once('DacteNFePHP.class.php');
//carrega a classe de conversões de txt para xml
require_once('ConvertCTePHP.class.php');

class AutoCTeNFePHP extends CTeNFePHP {
    
    /**
     * __construct
     * Método construtor da classe
     * Este método utiliza o arquivo de configuração localizado no diretorio config
     * para montar os diretórios e várias propriedades internas da classe, permitindo
     * automatizar melhor o processo de comunicação com o SEFAZ.
     * 
     * Este metodo pode estabelecer as configurações a partir do arquivo config.php ou 
     * através de um array passado na instanciação da classe.
     * 
     * @version 1.00
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param array $aConfig Opcional dados de configuração
     * @param number $mododebug Opcional 1-SIM ou 0-NÃO (0 default)
     * @return  boolean true sucesso false Erro
     */
    function __construct($aConfig='',$mododebug=0) {
       //passa os parâmetros para a classe base 
       parent::__construct($aConfig,$mododebug);
    }
    
    /**
     * autoTXTtoXML
     * Método para converter todas as nf em formato txt para o formato xml
     * localizadas na pasta "entradas". Os arquivos txt apoś terem sido
     * convertidos com sucesso são removidos da pasta.
     * Os arquivos txt devem ser nomeados como "<qualquer coisa>-nfe.txt"
     * @version 2.02
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param none
     * @return boolean true sucesso false Erro
     */
    public function autoTXTtoXML(){
        //varre pasta "entradas" a procura de CTes em txt
        $aName = $this->listDir($this->entDir,'*-cte.txt',false);
        // se foi retornado algum arquivo
        $totTXT = count($aName);
        if ( $totTXT > 0){
            for ( $x=0; $x < $totTXT; $x++ ) {
                //carrega cte em txt para converter em xml
                $filename = $this->entDir.$aName[$x];
                $newname = $this->temDir.$aName[$x];
                //instancia a classe de conversão
                $oXML = new ConvertCTePHP();
                //convere o arquivo
                $xml = $oXML->ctetxt2xml($filename);
                //salvar o xml
                $xmlname = $this->entDir.$oXML->chave.'-cte.xml';
                if ( !file_put_contents($xmlname, $xml) ){
                    $this->errStatus = true;
                    $this->errMsg .= "FALHA na gravação do CTe em xml.\n";
                    return false;
                } else {
                    //mover o txt para pasta temp
                    rename($filename,$newname);
                } //fim do teste de gravação
            } // fim do for
        } //fim do teste de contagem
        return true;
    } //fim autoTXTtoXML

    /**
     * autoSignCTe
     * Método para assinatura em lote das CTe em XML
     * Este método verifica todas as CTe existentes na pasta de ENTRADAS e as assina
     * após a assinatura ser feita com sucesso o arquivo XML assinado é movido para a pasta
     * ASSINADAS.
     * IMPORTANTE : Em ambiente Linux manter os nomes dos arquivos e terminações em LowerCase.
     *
     * @param  none
     * @return boolean true sucesso false Erro
     */
    public function autoSignCTe() {
        // Varre pasta "entradas" a procura de CTes
        $aName = $this->listDir($this->entDir, '*-cte.xml', false);
        // Se foi retornado algum arquivo
        $contadorXML = count($aName);
        if ($contadorXML > 0) {
            for ($x = 0; $x < $contadorXML; $x++) {
                // Carrega cte para assinar em uma strig
                $filename = $this->entDir . $aName[$x];
                // Mantenha desse jeito mesmo com um unico =
                // a atribuição da variavel e o teste são simultâneos
                if ($ctefile = file_get_contents($filename)) {
                    // Assinador usando somente o PHP da classe classCTe
                    // mantenha desse jeito mesmo com um unico =
                    // a atribuição da variavel e o teste são simultâneos
                    if ($signn = $this->signXML($ctefile, 'infCte') ) {
                        // XML retornado gravar
                        $file = $this->assDir . $aName[$x];
                        if (!file_put_contents($file, $signn)) {
                            $this->errStatus = true;
                            return false;
                        } else {
                            unlink($filename);
                        } // Fim do teste de gravação
                    } // Fim do teste da assinatura
                } // Fim do teste de leitura
            } // Fim do for
        } // Fim do teste de contagem
        return true;
    } // Fim autoSignCTe

    /**
     * autoValidCTe
     * Método validador em lote das CTe em XML já assinadas.
     * As CTe são validadas somente após que a TAG assinatura seja postada no XML, caso contrario
     * gerará um erro.
     *
     * As CTes, em principio podem ser assinadas sem grande perda de performance do sistema,
     * desde que o numero de NFe geradas seja relativamente pequeno.
     * Caso o numero seja muito grande (acima de 50 CTe de cada por minuto) talvez seja
     * interessante fazer alterações no sistema para incluir a TAG de assinatura em branco
     * e validar antes de assinar.
     *
     * Este método verifica todas as CTe que existem na pasta de ASSINADAS e processa a validação
     * com o shema XSD. Caso a CTe seja valida será movida para a pasta VALIDADAS, caso contrario
     * será movida para a pasta REPROVADAS.
     *
     * @param   none
     * @return  boolean true sucesso false Erro
     */
    public function autoValidCTe() {
        // Varre pasta "assinadas"
        $aName = $this->listDir($this->assDir, '*-cte.xml', false);
        // Se foi retornado algum arquivo
        $contadorXML = count($aName);
        if ($contadorXML > 0) {
            for ($x = 0; $x < $contadorXML; $x++) {
                // Carrega CTe para validar em uma string
                $filename = $this->assDir . $aName[$x];
                if ($ctefile = file_get_contents($filename)) {
                    // Validar
                    // Como os arquivos xsd são atualizados e tem sua versão alterada
                    // devemos buscar este arquivo da versão correta
                    // para isso temos que obter o numero da versão na própria nfe
                    $xmldoc = new DOMDocument();
                    $xmldoc->preservWhiteSpace = false; // Elimina espaços em branco
                    $xmldoc->formatOutput = false;
                    $xmldoc->loadXML($ctefile, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                    $root = $xmldoc->documentElement;
                    // Extrair a tag com o numero da versão da CTe
                    $node = $xmldoc->getElementsByTagName('infCte')->item(0);
                    // Obtem a versão do layout da CTe
                    $ver = trim($node->getAttribute("versao"));
                    // Buscar o nome do scheme
                    $filexsd = $this->listDir($this->xsdDir . $this->cteSchemeVer . DIRECTORY_SEPARATOR, 'cte_v*.xsd', true);
                    if (!$filexsd[0]) {
                        $this->errMsg = 'Erro na localização do shema xsd';
                        $this->errStatus = true;
                        return false;
                    }
					$aError = array();
                    //$aRet = $this->validXML($ctefile, $filexsd[0], $aError);
                    //if ($aRet['status']) {
					if ( $this->validXML($ctefile, $filexsd[0], $aError) ) {
                        // Validado => transferir para pasta validados
                        $file = $this->valDir . $aName[$x];
                        if (!file_put_contents($file, $ctefile))
                            $this->errStatus = false;
                        else
                            unlink($filename);
                    } else {
                        // CTe com erros transferir de pasta rejeitadas
                        $file = $this->rejDir . $aName[$x];
                        $this->errStatus = true;
                        $this->errMsg .= $aName[$x] . ' ... ' . $aError['error'] . "\n";
                        if (!file_put_contents($file, $ctefile))
                            $this->errStatus = true;
                        else
                            unlink($filename);
                        // Fim teste de gravação
                        return false;
                    } // Fim validação
                } // Fim teste de leitura
            } // Fim for
        } // Fim do teste de contagem
        return true;
    } // Fim autoValidCTe

    /**
     * autoEnvCTe
     * Este método procura por CTe's na pasta VALIDADAS, se houver alguma envia para a SEFAZ
     * e em saso de sucesso no envio move o arquivo para a pasta das enviadas
     * ATENÇÃO : Existe um limite para o tamanho do arquivo a ser enviado ao SEFAZ
     *
     * @param   none
     * @return  mixed boolean false erro ou string $recibo
     */
    public function autoEnvCTe(){
        // Varre a pasta de validadas
        $aName = $this->listDir($this->valDir, '*-cte.xml', false);
        // Se houver algum arquivo *-cte.xml continua, caso contrario sai
        $n = count($aName);
        if ($n > 0) {
            // Determina o numero de grupos de envio com 10 notas por grupo
            $k = ceil($n / 10);
            // os conhecimentos localizados na pasta validadas serão enviadas em
            // grupos de até 10 cte de cada vez, sim este é um valor arbritrario eu quis até 10
            for ($i = 0 ; $i < $k ; $i++) {
                //limpa a matriz com as notas fiscais
                $aCTE= null;
                for ($x = $i*10; $x < (($i+1)*10); $x++) {
                    if ($x < $n ){
                        $filename = $this->valDir . $aName[$x];
                        $ctefile = file_get_contents($filename);
                        $aCTE[] = $ctefile;
                    }
                }
                // Criar o numero do lote baseado no microtime
                $num = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
                // Enviar os conhecimentos
                if ($ret = $this->sendLot($aCTE, $num, $this->modSOAP)) {
                    // ['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'']                    
                    // Verificar o status do envio
                    if ($ret['bStat']){
                        // Obter o numero do recibo da SEFAZ
                        $recibo = $ret['nRec'];
                        // Mover os conhecimentos do lote para o diretorio de enviados
                        // para cada em $aNames[] mover para $this->envDir
                        for ($x = $i*10 ; $x < (($i+1)*10); $x++) {
                            if ($x < $n) {
                               if(!rename($this->valDir . $aName[$x], $this->envDir . $aName[$x])) {
                                    $this->errStatus = true;
                                    $this->errMsg .= ' Falha na movimentação do CTe dos "validados" para "enviados"!! ';
                                    return false;
                                }
                            }
                        } // Fim for rename
                    } else {
                        $this->errStatus = true;
                        return false;
                    }
                 } else {
                    $this->errStatus = true;
                    $this->errMsg .= ' Erro no envio do lote de CTe!! ';
                    return false;
                 }
            }
        } else {
            $this->errStatus = true;
            $this->errMsg = ' Não há CTe para enviar!! ';
            return false;
        }
        return $recibo;
    } // fim autoEnvCTe

    /**
     * autoProtCTe
     * Este método localiza os CTe enviadas na pasta ENVIADAS e solicita o prococolo
     * de autorização destes CTe's
     *
     * Caso haja resposta (aprovando, denegando ou rejeitando) o método usa os dados de
     * retorno para localizar o CTe em xml na pasta de ENVIADAS e inclui no XML a tag cteProc com os dados
     * do protocolo de autorização.
     *  - Em caso de aprovação as coloca na subpasta APROVADAS e remove tanto o xml do CTe
     *    da pasta ENVIADAS como o retorno da consulta em TEMPORARIAS.
     *  - Em caso de rejeição coloca as coloca na subpasta REJEITADAS e remove da pasta ENVIADAS e TEMPORARIAS.
     *  - Em caso de denegação coloca as coloca na subpasta DENEGADAS e remove da pasta ENVIADAS e TEMPORARIAS.
     *
     * Caso não haja resposta ainda não faz nada.
     *
     * @param   none
     * @return  array   [n]['cStat']['xMotivo']['ctepath']
     */
    public function autoProtCTe() {
        //condição inicial da variável de retorno
        $aRetorno = array(0=>array('cStat'=>'','xMotivo'=>'','ctepath'=>''));
        $n = 0;
        //varre a pasta de enviadas
        $aName = $this->listDir($this->envDir,'*-cte.xml',false);
        //se houver algum arquivo *-cte.xml continua, caso contrario sai
        if ( count($aName) > 0 ) {
            //para cada arquivo nesta pasta solicitar o protocolo
            foreach ( $aName as $file ) {
                $idCTe = substr($file,0,44);
                $cteFile = $this->envDir.$file;
                //primeiro verificar se o protocolo existe na pasta temporarias
                $protFile = $this->temDir.$idCTe.'-cteprot.xml';
                if (file_exists($protFile)){
                    $docxml = file_get_contents($protFile);
                    $dom = new DOMDocument(); //cria objeto DOM
                    $dom->formatOutput = false;
                    $dom->preserveWhiteSpace = false;
                    $dom->loadXML($docxml);
                    //pagar o cStat e xMotivo do protocolo
                    $aRet['cStat'] = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
                    $aRet['xMotivo'] = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
                    $aRet['bStat'] = true;
                } else {
                    //caso não exista então buscar pela chave do CTe
                    $aRet = $this->getProtocol('',$idCTe,$this->tpAmb,$this->modSOAP);
                }    
                if ( $aRet['cStat'] == 100) {
                    //CTe aprovado
                    $pasta = $this->aprDir;
                }//endif
                if ( $aRet['cStat'] == 110) {
                    //CTe denegado
                    $pasta = $this->denDir;
                }//endif
                if ( $aRet['cStat'] > 199 ) {
                    //CTe reprovado
                    $pasta = $this->repDir;
                    //mover o CTe para a pasta repovadas
                    rename($cteFile, $pasta.$idCTe.'-cte.xml');
                }//endif
                if ( $aRet['bStat'] ) {
                    //montar a CTe com o protocolo
                    if ( is_file($protFile) && is_file($cteFile) ) {
                        //se aprovada ou denegada adicionar o protocolo e mover o arquivo
                        if ($aRet['cStat'] == 100 || $aRet['cStat'] == 110 ) {
                            $proccte = $this->addProt($cteFile,$protFile);
                            //salvar a CTe com o protocolo na pasta
                            if ( file_put_contents($pasta.$idCTe.'-cte.xml', $proccte) ) {
                                //se o arquivo foi gravado na pasta destino com sucesso
                                //remover os arquivos das outras pastas
                                unlink($cteFile);
                            } //endif
                        }//endif cStat   
                    } //endif is_file
                } //endif bStat
                $aRetorno[$n]['cStat'] = $aRet['cStat'];
                $aRetorno[$n]['xMotivo'] = $aRet['xMotivo'];
                $aRetorno[$n]['ctepath'] = $pasta.$idCTe.'-cte.xml';
                $n++;
            }//endforeach
        } else {
            //não há CTe para protocolar na pasta de enviadas
            $this->errStatus = true;
            $this->errMsg = ' Não existe CTe pronta para protocolar na pasta enviadas!! ';
        }//endif
        return $aRetorno;
    }
    // Fim autoProtCTe

} //fim da classe
?>

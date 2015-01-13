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
 * @name      AutoToolsNFePHP
 * @version   1.0.2
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 * 
 * 
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
//carrega a classe principal da API 
require_once('ToolsNFePHP.class.php');
//carrega a classe de conversões de txt para xml e vice-versa
require_once('ConvertNFePHP.class.php');
//carrega a classe de impressao da DANFE
require_once('DanfeNFePHP.class.php');
//carrega a classe de envio de emails
require_once('MailNFePHP.class.php');

class AutoToolsNFePHP extends ToolsNFePHP {
    
    
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
        //varre pasta "entradas" a procura de NFes em txt
        $aName = $this->listDir($this->entDir,'*-nfe.txt',false);
        // se foi retornado algum arquivo
        $totTXT = count($aName);
        if ( $totTXT > 0){
            for ( $x=0; $x < $totTXT; $x++ ) {
                //carrega nfe em txt para converter em xml
                $filename = $this->entDir.$aName[$x];
                $newname = $this->temDir.$aName[$x];
                //instancia a classe de conversão
                $oXML = new ConvertNFePHP();
                //convere o arquivo
                $xml = $oXML->nfetxt2xml($filename);
                //salvar o xml
                $xmlname = $this->entDir.$oXML->chave.'-nfe.xml';
                if ( !file_put_contents($xmlname, $xml) ){
                    $this->errStatus = true;
                    $this->errMsg .= "FALHA na gravação da NFe em xml.\n";
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
     * autoEnvNFe
     * Este método procura por NFe's na pasta VALIDADAS, se houver alguma envia para a SEFAZ
     * e em saso de sucesso no envio move o arquivo para a pasta das enviadas
     * ATENÇÃO : Existe um limite para o tamanho do arquivo a ser enviado ao SEFAZ
     *
     * @version 2.04
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param none
     * @return mixed false erro ou string $recibo
     */
    public function autoEnvNFe(){
        //estabelece condição inicial do retorno
        $recibo = '';
        //varre a pasta de validadas
        $aName = $this->listDir($this->valDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        $n = count($aName);
        if ( $n > 0 ) {
            //determina o numero de grupos de envio com 10 notas por grupo
            $k = ceil($n/10);
            // as notas localizadas na pasta validadas serão enviadas em
            // grupos de até 10 notas de cada vez, sim este é um valor arbritrario eu quis até 10
            for ($i = 0 ; $i < $k ; $i++) {
                //limpa a matriz com as notas fiscais
                $aNFe= null;
                for ( $x = $i*10 ; $x < (($i+1)*10) ;$x++ ){
                    if ($x < $n ){
                        $filename = $this->valDir.$aName[$x];
                        $nfefile = file_get_contents($filename);
                        $aNFe[] = $nfefile;
                    }
                }
                //obter o numero do ultimo lote enviado
                $num = $this->__getNumLot();
                //incrementa o numero
                $num++;
                //enviar as notas
                if ($ret = $this->sendLot($aNFe,$num,$this->modSOAP)){
                    //incrementa o numero do lote no controle
                    if (!$this->__putNumLot($num)){
                        $this->errStatus = true;
                        $this->errMsg .= "Falha na Gravação do numero do lote de envio!!\n";
                        return false;
                    }
                    //['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'']                    
                    //verificar o status do envio
                    if ($ret['bStat']){
                        //pegar o numero do recibo da SEFAZ
                        $recibo = $ret['nRec'];
                        //mover as notas do lote para o diretorio de enviadas
                        //para cada em $aNames[] mover para $this->envDir
                        for ( $x = $i*10 ; $x < (($i+1)*10) ;$x++ ){
                            if ($x < $n ){
                               if( !rename($this->valDir.$aName[$x],$this->envDir.$aName[$x]) ){
                                    $this->errStatus = true;
                                    $this->errMsg .= "Falha na movimentação da NFe das validadas para enviadas !!\n";
                                    return false;
                                }
                            }
                        } //fim for rename
                    } else {
                        $this->errStatus = true;
                        return false;
                    }
                 } else {
                    $this->errStatus = true;
                    $this->errMsg .= "Erro no envio do lote de NFe!!\n";
                    return false;
                 }
            }
        } else {
            //não há notas para enviar
            $this->errStatus = true;
            $this->errMsg = "Não existem notas prontas para envio na pasta das validadas!!\n";
            return false;
        }
        return $recibo;
    } // fim autoEnvNFe
    
    /**
     * autoEnvNFeImproved
     * Este método localiza as NFe validadas e efetua a montagem dos lotes de envio
     * envia esses lotes para a SEFAZ, move essas NFe para a pasta de enviadas, 
     * aguarda alguns segundos para permitir o processamento na SEFAZ e em seguida
     * requisita a situação das NFe pelo numero do recibo. Em caso de sucesso anexa o 
     * protocolo e coloca a NF na pasta de aprovadas, reprovadas ou denegadas, conforme o 
     * status de cada uma.
     *      
     * @version 1.03
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param integer $maxLoop Numero maximo de tentativas para buscar o staus da NFe
     * @param integer $minSec Tempo de pausa entre cada consulta em segundos
     * @return mixed false se ocorrer erro ou array('chave','cStat','xMotivo')
     */
    public function autoEnvNFeImproved($maxLoop=10,$minSec=2){
        $aResp = array();
        //busca as NFe e monta o lote
        //varre a pasta de validadas
        $aName = $this->listDir($this->valDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        $n = count($aName);
        if ( $n == 0 ) {
            $this->errStatus = true;
            $this->errMsg .= "Não há NFe validadas e prontas para envio!!\n";
            return false;
        }
        //existem notas para enviar
        //iniciar o loop de envio para cada NFe encontrada
        for ( $x=0 ; $x<$n ; $x++ ){
            $aNFe = null;
            $filename = $this->valDir.$aName[$x];
            $chaveNFe = substr($filename, strripos($filename, DIRECTORY_SEPARATOR) + 1, 44);
            $nfefile = file_get_contents($filename);
            $aNFe[] = $nfefile;
            //obter o numero do ultimo lote enviado
            $num = $this->__getNumLot();
            //incrementa o numero
            $num++;
            //alternativamente pode ser usado o script abaixo para geração do numero de lote
            //$num = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
            //enviar as notas
            if ($ret = $this->sendLot($aNFe,$num,$this->modSOAP)){
                //verificar o status do envio
                if ($ret['bStat'] == true && $ret['cStat'] == 103){
                    //pegar o numero do recibo da SEFAZ
                    $recibo = $ret['nRec'];
                    //incrementa o numero do lote no controle
                    //comentar caso não use o controle de lote por arquivo
                    if ($this->__putNumLot($num)){
                        $this->errStatus = true;
                        $this->errMsg .= "Falha na Gravação do numero do lote de envio!!\n";
                        return false;
                    }
                    //como temos o recibo iniciar o loop
                    $loop = 0;
                    while ($loop < $maxLoop){
                        //incrementar contagem
                        $loop++;
                        //atrasar a consulta para permitir processamento da SEFAZ
                        sleep($minSec);
                        //consultar o ststus da NFe
                        if($retProt = $this->getProtocol($recibo,'', $this->tpAmb, $this->modSOAP)){
                            if ($retProt['bStat'] == true){
                                if ($retProt['cStat'] == 106){
                                    $this->errStatus = true;
                                    $this->errMsg .= "Lote não localizado !!\n";
                                    return false;
                                }
                                if ($retProt['cStat'] > 106){
                                    $this->errStatus = true;
                                    $this->errMsg .= "Erro na consulta do lote !!\n" . $retProt['cStat'];
                                    return false;
                                }
                                if ($retProt['cStat'] == 104){
                                    //o protocolo foi retornado
                                    $aProt = $retProt['aProt'][$x];
                                    $protFile = $this->temDir.$chaveNFe.'-prot.xml';
                                    $loop = $maxLoop+1; //sair do laço
                                    $aResp[$x] = array();
                                    $aResp[$x]['chave'] = $chaveNFe;
                                    $aResp[$x]['cStat'] = $aProt['cStat'];
                                    $aResp[$x]['xMotivo'] = $aProt['xMotivo'];
                                    //pegar o cStat
                                    if ($aProt['cStat'] == 100){
                                        //aprovado
                                        $dirDest = $this->aprDir;
                                    }
                                    if ($aProt['cStat'] == 110){
                                        //denegado
                                        $dirDest = $this->denDir;
                                    }
                                    if ($aProt['cStat'] > 110){
                                        //reprovado
                                        $dirDest = $this->repDir;
                                        //mover a nota para reprovadas
                                        rename($filename, $dirDest.$chaveNFe.'-nfe.xml');
                                    } else {
                                        //não reprovado
                                        if (file_exists($protFile)){
                                            $procnfe = $this->addProt($filename,$protFile);
                                            //salvar a NFe com o protocolo na pasta destino
                                            if ( file_put_contents($dirDest.$chaveNFe.'-nfe.xml', $procnfe) ) {
                                                //remover o arquivo antigo sem o protocolo
                                                unlink($filename);
                                            }
                                        }
                                    }    
                                }
                            } else {
                                $this->errStatus = true;
                                $this->errMsg .= "Falha na consulta do protocolo!!\n";
                                return false;
                            }//fim bStat
                        } else {
                            $this->errStatus = true;
                            $this->errMsg .= "Falha na consulta do protocolo!!\n";
                            return false;
                        }//fim consulta protocolo
                    }//fim do loop de consulta do status da nfe
                } else {
                    $this->errStatus = true;
                    $this->errMsg .= "Erro no envio do lote de NFe!!\n";
                    return false;
                }//fim if verifica retorno envio lote
            } else {
                $this->errStatus = true;
                $this->errMsg .= "Erro no envio do lote de NFe!!\n";
                return false;
            }//fim envio lote
        } //fim for das NFe
        return $aResp;
    } //fim autoEnvNFeImproved    

    /**
     * autoProtNFe
     * Este método localiza as NFe enviadas na pasta ENVIADAS e solicita o prococolo
     * de autorização destas NFe's
     *
     * Caso haja resposta (aprovando, denegando ou rejeitando) o método usa os dados de
     * retorno para localizar a NFe em xml na pasta de ENVIADAS e inclui no XML a tag nfeProc com os dados
     * do protocolo de autorização.
     *  - Em caso de aprovação as coloca na subpasta APROVADAS e remove tanto o xml da NFe
     *    da pasta ENVIADAS como o retorno da consulta em TEMPORARIAS.
     *  - Em caso de rejeição coloca as coloca na subpasta REJEITADAS e remove da pasta ENVIADAS e TEMPORARIAS.
     *  - Em caso de denegação coloca as coloca na subpasta DENEGADAS e remove da pasta ENVIADAS e TEMPORARIAS.
     *
     * Caso não haja resposta ainda não faz nada.
     *
     * @version 2.04
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  none
     * @return array   [n]['cStat']['xMotivo']['nfepath']
     */
    public function autoProtNFe(){
        //condição inicial da variável de retorno
        $aRetorno = array(0=>array('cStat'=>'','xMotivo'=>'','nfepath'=>''));
        $n = 0;
        //varre a pasta de enviadas
        $aName = $this->listDir($this->envDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        if ( count($aName) > 0 ) {
            //para cada arquivo nesta pasta solicitar o protocolo
            foreach ( $aName as $file ) {
                $idNFe = substr($file,0,44);
                $nfeFile = $this->envDir.$file;
                //primeiro verificar se o protocolo existe na pasta temporarias
                $protFile = $this->temDir.$idNFe.'-prot.xml';
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
                    //caso não exista então buscar pela chave da NFe
                    $aRet = $this->getProtocol('',$idNFe,$this->tpAmb,$this->modSOAP);
                }    
                if ( $aRet['cStat'] == 100) {
                    //NFe aprovada
                    $pasta = $this->aprDir;
                }//endif
                if ( $aRet['cStat'] == 110) {
                    //NFe denegada
                    $pasta = $this->denDir;
                }//endif
                if ( $aRet['cStat'] > 199 ) {
                    //NFe reprovada
                    $pasta = $this->repDir;
                    //mover a NFe para a pasta repovadas
                    rename($nfeFile, $pasta.$idNFe.'-nfe.xml');
                }//endif
                if ( $aRet['bStat'] ) {
                    //montar a NFe com o protocolo
                    if ( is_file($protFile) && is_file($nfeFile) ) {
                        //se aprovada ou denegada adicionar o protocolo e mover o arquivo
                        if ($aRet['cStat'] == 100 || $aRet['cStat'] == 110 ) {
                            $procnfe = $this->addProt($nfeFile,$protFile);
                            //salvar a NFe com o protocolo na pasta
                            if ( file_put_contents($pasta.$idNFe.'-nfe.xml', $procnfe) ) {
                                //se o arquivo foi gravado na pasta destino com sucesso
                                //remover os arquivos das outras pastas
                                unlink($nfeFile);
                            } //endif
                        }//endif cStat   
                    } //endif is_file
                } //endif bStat
                $aRetorno[$n]['cStat'] = $aRet['cStat'];
                $aRetorno[$n]['xMotivo'] = $aRet['xMotivo'];
                $aRetorno[$n]['nfepath'] = $pasta.$idNFe.'-nfe.xml';
                $n++;
            }//endforeach
        } else {
            //não há notas para protocolar na pasta de enviadas
            $this->errStatus = true;
            $this->errMsg = "Não existem notas prontas para protocolar na pasta enviadas!!\n";
        }//endif
        return $aRetorno;
    }//fim autoProtNFe

    /**
     * autoPrintSend
     * Este método deve imprmir automaticamente as DANFEs de todas as
     * NFe localizadas na pasta aprovadas, enviar o email ao destinatário
     * com a DANFE em pdf e o arquivo xml e ainda mover a NFe em xml
     * para o diretorio de armazenamento com o ANOMES da nota para facilitação
     * da adminstração e backup
     *
     * @version   2.07
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  boolean $fixpdf Indica de deve ser gravado ou não o pdf da NFe
     * @return boolean True se OK ou False se falha
    */
    public function autoPrintSend($fixpdf=false){
        //varre a pasta de enviadas/aprovadas
        $aNApr = $this->listDir($this->aprDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        if ( count($aNApr) > 0 ) {
            //para cada arquivo nesta pasta imprimir a DANFE em pdf
            // e enviar para a printer
            // SÓ FUNCIONA ATÉ 2099 !!!!!
            foreach ( $aNApr as $file ) {
                $anomes = '20'.substr($file,2,4);
                $docxml = file_get_contents($this->aprDir.$file);
                $danfe = new DanfeNFePHP($docxml,$this->danfepaper,$this->danfeform,$this->danfelogopath,'I','',$this->danfefont);
                $id = (string) $danfe->montaDANFE();
                $pdfName = $id.'.pdf';
  				//carrega a DANFE como uma string para gravação no diretorio escolhido
                $pdfFile = (string) $danfe->printDANFE($this->pdfDir.$pdfName,'S');
                if ($pdfFile != ''){
                    //grava a DANFE como pdf no diretorio
                    if (!file_put_contents($this->pdfDir.$pdfName,$pdfFile)){
                        //houve falha na gravação
                        $this->errMsg = "Falha na gravação do pdf.\n";
                        $this->errStatus = true;
                    } else {
                        //em caso de sucesso, verificar se foi definida a printer se sim imprimir
                        //este comando de impressão funciona tanto em linux como em wndows se o 
                        //ambiente estiver corretaente preparado
			if ( $this->danfeprinter != '' ) {
                            $command = "lpr -P $this->danfeprinter $this->pdfDir$pdfName";
                            system($command);
                        }
                    }
		} else {
                    //houve falha na geração da DANFE
                    $this->errMsg = "Falha na geração da DANFE.\n";
                    $this->errStatus = true;
		}
                //arquivo da NFe com o protocolo
                $dom = new DOMDocument(); //cria objeto DOM
                $dom->formatOutput = false;
                $dom->preserveWhiteSpace = false;
                $dom->loadXML($docxml);
                $ide        = $dom->getElementsByTagName("ide")->item(0);
                $emit       = $dom->getElementsByTagName("emit")->item(0);
                $dest       = $dom->getElementsByTagName("dest")->item(0);
                $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
                $obsCont    = $dom->getElementsByTagName("obsCont")->item(0);
                $infNFe     = $dom->getElementsByTagName('infNFe')->item(0);
                //obtem a versão do layout da NFe
                $ver = trim($infNFe->getAttribute("versao"));
                $razao = utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue);
                $numero = str_pad($ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
                $serie = str_pad($ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
                $emitente = utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue);
                $vtotal = number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
                $email = '';
                $contato = '';
                //NFe ver 1.10 - obter o email de envio
                //na proxima revisão não será mais inclusa a verificação da
                //versão 1.10 que passará a ser resconsiderada
                if($ver =='1.10'){
                    if (isset($obsCont)){
                        foreach ($obsCont as $obs){
                            $campo =  $obsCont->item($i)->getAttribute("xCampo");
                            $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                            if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                                $email .= $xTexto.',';
                            }
                            $i++;
                        }
                    }
                } else {
                    //NFe ver 2.00
                    $email = !empty($dest->getElementsByTagName("email")->item(0)->nodeValue) ? utf8_decode($dest->getElementsByTagName("email")->item(0)->nodeValue) : '';
                    if (isset($obsCont)){
                        foreach ($obsCont as $obs){
                            $campo =  $obsCont->item($i)->getAttribute("xCampo");
                            $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                            if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                                $email .= $xTexto.',';
                            }
                            $i++;
                        }
                    }
               } //endif
               if ($email != '' ) {
                    //montar a matriz de dados para envio do email
                    $aEMail = array('emitente'=>$emitente,'para'=>$email,'contato'=>'','razao'=>$razao,'numero'=>$numero,'serie'=>$serie,'vtotal'=>$vtotal);
                    //inicalizar a classe de envio
                    $nfeMail = new MailNFePHP($this->aMail);
                    if ( !$nfeMail->sendNFe($docxml,$pdfFile,$file,$pdfName,$aEMail) ){
                        $this->errMsg = "Falha no envio do email ao destinatário!!\n";
                        $this->errStatus = true;
                    }
                }
                //mover o arquivo xml para a pasta de arquivamento identificada com o ANOMES
                $diretorio = $this->aprDir.$anomes.DIRECTORY_SEPARATOR;
                if ( !is_dir($diretorio) ) {
                      mkdir($diretorio, 0777);
                }
                rename($this->aprDir.$file,$diretorio.$file);
                //apagar o pdf criado para envio
                if (!$fixpdf){
                    if (is_file($this->pdfDir.$pdfName)){
                        unlink($this->pdfDir.$pdfName);
                    }
                }    
            } //end foreach
        } //endif
        return true;
    } //fim da função autoPrintDANFE

    /**
     * autoSignNFe
     * Método para assinatura em lote das NFe em XML
     * Este método verifica todas as NFe existentes na pasta de ENTRADAS e as assina
     * após a assinatura ser feita com sucesso o arquivo XML assinado é movido para a pasta
     * ASSINADAS.
     * IMPORTANTE : Em ambiente Linux manter os nomes dos arquivos e terminações em LowerCase.
     *
     * @version 2.11
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  none
     * @return boolean true sucesso false Erro
     */
    public function autoSignNFe() {
        //varre pasta "entradas" a procura de NFes
        $aName = $this->listDir($this->entDir,'*-nfe.xml',false);
        // se foi retornado algum arquivo
        $totXML = count($aName);
        if ( $totXML > 0){
            for ( $x=0; $x < $totXML; $x++ ) {
                //carrega nfe para assinar em uma strig
                $filename = $this->entDir.$aName[$x];
                //mantenha desse jeito mesmo com um unico =
                //a atribuição da variavel e o teste são simultâneos
                if ( $nfefile = file_get_contents($filename) ) {
                    //assinador usando somente o PHP da classe classNFe
                    //mantenha desse jeito mesmo com um unico =
                    //a atribuição da variavel e o teste são simultâneos
                    if ( $signn = $this->signXML($nfefile, 'infNFe') ) {
                        //xml retornado gravar
                        $file = $this->assDir . $aName[$x];
                        if ( !file_put_contents($file, $signn) ) {
                            $this->errStatus = true;
                            return false;
                        } else {
                            unlink($filename);
                        } //fim do teste de gravação
                    } //fim do teste da assinatura
                } //fim do teste de leitura
            } //fim do for
        } //fim do teste de contagem
        return true;
    } //fim autoSignNFe

    /**
     * autoValidNFe
     * Método validador em lote das NFe em XML já assinadas.
     * As NFe são validadas somente após que a TAG assinatura seja postada no XML, caso contrario
     * gerará um erro.
     *
     * As NFes, em principio podem ser assinadas sem grande perda de performance do sistema,
     * desde que o numero de NFe geradas seja relativamente pequeno.
     * Caso o numero seja muito grande (acima de 50 NFe de cada por minuto) talvez seja
     * interessante fazer alterações no sistema para incluir a TAG de assinatura em branco
     * e validar antes de assinar.
     *
     * Este método verifica todas as NFe que existem na pasta de ASSINADAS e processa a validação
     * com o shema XSD. Caso a NFe seja valida será movida para a pasta VALIDADAS, caso contrario
     * será movida para a pasta REPROVADAS.
     *
     * @version 2.0.3
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  none
     * @return boolean true sucesso false Erro
     */
    public function autoValidNFe() {
        //varre pasta "assinadas"
        $aName = $this->listDir($this->assDir,'*-nfe.xml',false);
        // se foi retornado algum arquivo
        $totXML = count($aName);
        if ( $totXML > 0 ){
            for ( $x=0; $x < $totXML; $x++ ) {
                //carrega nfe para validar em uma string
                $filename = $this->assDir.$aName[$x];
                if ( $nfefile = file_get_contents($filename) ) {
                    //validar
                    //como os arquivos xsd são atualizados e tem sua verção alterada
                    //devemos burcar este arquivo da versão correta
                    //para isso temos que obter o numero da versão na própria nfe
                    $xmldoc = new DOMDocument();
                    $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
                    $xmldoc->formatOutput = false;
                    $xmldoc->loadXML($nfefile,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                    $root = $xmldoc->documentElement;
                    //extrair a tag com o numero da versão da NFe
                    $node = $xmldoc->getElementsByTagName('infNFe')->item(0);
                    //obtem a versão do layout da NFe
                    $ver = trim($node->getAttribute("versao"));
                    //buscar o nome do scheme
                    $filexsd = $this->listDir($this->xsdDir . $this->schemeVer. DIRECTORY_SEPARATOR,'nfe_v*.xsd',true);
                    if (!$filexsd[0]) {
                        $this->errMsg = "Erro na localização do shema xsd.\n";
                        $this->errStatus = true;
                        return false;
                    }
                    $aErr = array();
                    $bRet = $this->validXML($nfefile,$filexsd[0],$aErr);
                    if ( $bRet ) {
                        // validado => transferir para pasta validados
                        $file = $this->valDir . $aName[$x];
                        if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = false;
                        } else {
                            unlink($filename);
                        }
                    } else {
                        $sErr = '';
                        foreach ($aErr as $e){
                            $sErr .= $e . "\n";
                        }
                        //NFe com erros transferir de pasta rejeitadas
                        $file = $this->rejDir . $aName[$x];
                        $this->errStatus = true;
                        $this->errMsg .= $aName[$x].' ... '.$sErr."\n";
                       if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = true;
                        } else {
                            unlink($filename);
                        } //fim teste de gravação
                        return false;
                    } //fim validação
                } //fim teste de leitura
            }//fim for
        } // fim do teste de contagem
        return true;
    } //fim autoValidNFe

} //fim da classe Auxiliar

?>

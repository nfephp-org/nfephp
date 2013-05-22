<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela
 * Fundação para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @package     NFePHP
 * @name        DocumentoNFePHP.interface.php
 * @version     2.04
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2013 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto Spadim <roberto at spadim dot com dot br>
 * 
 *
 * Este arquivo contem funções para identificar conteúdo de arquivos 
 * 2.04 - correção de bug para identificar nfe
 * 2.03 - só força https quando for nfc-e, se for nfe ou cte, pode ser um site do emitente do documento
 * 2.02 - evento de nfc-e (sabe se é nfe ou nfc-e pela chave de acesso)
 *        corrigido pdf/img que retornava sempre nfce nos modelos 55,65,57
 * 2.01 - adicionado evento de CTE (precisa ser testado, ainda não tem xml disso em teste, nem em produção)
 * 2.00 - alterado função IdentifyFile para retornar um array 
 *            adicionado reconhecimento de pdf e imagens de danfes, precisa de dois executaveis externos:
 *                zbarimg (versão 0.10 testada e funcionando)
 *                imagemagick versão 9 testada e funcionando (pode ser necessário ghostscript no windows)
 *            separado em varias funções para facilitar a leitura
 *                adicionado algumas funções para verificar digito verificador de chave de acesso (verificação minima para os arquivos PDF e imagem)
 *            adicionado tipo array quando tem varios formatos dentro de um arquivo PDF (danfe+dacte por exemplo)
 * 1.04 - quando é NFe, verifica se é uma NFC-e
 * 1.03 - adicionado função para extrair do xml da nfeb2b, o dom da nfe (nfeProc) e o documento do b2b (NFeB2B)
 * 1.02 - adicionado nfeb2b
 * 
 */
// tipos arquivos 
if(!defined('NFEPHP_TIPO_ARQUIVO_DESCONHECIDO')){
    define('NFEPHP_TIPO_ARQUIVO_ARRAY'                  ,-1);
    define('NFEPHP_TIPO_ARQUIVO_DESCONHECIDO'           ,0);
    define('NFEPHP_TIPO_ARQUIVO_NFe'                    ,1);
    define('NFEPHP_TIPO_ARQUIVO_NFe_SEM_PROTOCOLO'      ,2);
    define('NFEPHP_TIPO_ARQUIVO_CTe'                    ,3);
    define('NFEPHP_TIPO_ARQUIVO_CTe_SEM_PROTOCOLO'      ,4);
    define('NFEPHP_TIPO_ARQUIVO_NFe_NFeB2B'             ,5);
    define('NFEPHP_TIPO_ARQUIVO_NFCe'                   ,6);
    define('NFEPHP_TIPO_ARQUIVO_NFCe_SEM_PROTOCOLO'     ,7);    // será que existe?!
    
    define('NFEPHP_TIPO_ARQUIVO_EventoNFe'              ,100);    // modelo novo (v2 e v3) - modelo 55 -nfe
    define('NFEPHP_TIPO_ARQUIVO_NFe_procCancNFe'        ,101);    // modelo antigo de cancelamento (v1)
    define('NFEPHP_TIPO_ARQUIVO_CTe_procCancCTe'        ,102);    // modelo antigo (v1.04)
    define('NFEPHP_TIPO_ARQUIVO_EventoCTe'              ,103);    // modelo novo (v2.0)
    define('NFEPHP_TIPO_ARQUIVO_EventoNFCe'             ,104);    // modelo novo (v2 e v3) - este é para documento modelo 65 - nfce (será q vai ser utilizado?)
    
    define('NFEPHP_TIPO_ARQUIVO_NFe_InutFaixa'          ,200);
    define('NFEPHP_TIPO_ARQUIVO_CTe_InutFaixa'          ,201);
    
    define('NFEPHP_TIPO_ARQUIVO_TXT_NFE'                ,300);
    define('NFEPHP_TIPO_ARQUIVO_TXT_CTE'                ,301);
    
    define('NFEPHP_TIPO_ARQUIVO_PDF_NFE'                ,400);
    define('NFEPHP_TIPO_ARQUIVO_PDF_NFCE'               ,401);
    define('NFEPHP_TIPO_ARQUIVO_PDF_CTE'                ,402);
}
if(!class_exists('IdentifyNFePHP')){
    class IdentifyNFePHP {
        var $path_zbarimg='';
        var $path_convert='';
        var $path_tmp='';
        function __construct($path_zbarimg='',$path_convert='',$path_tmp=''){
            if($path_zbarimg!='')$this->path_zbarimg=$path_zbarimg;
            if($path_convert!='')$this->path_convert=$path_convert;
            if($path_tmp!='')$this->path_tmp=$path_tmp;
        }
        function _IdentifyQRCode($codigo_barra){
/*
QR-Code:https://nfce.set.rn.gov.br/consultarNFCe.aspx?chNFe=24130411982113000237650020000000071185945690&nVersao=100&tpAmb=2&dhEmi=323031332d30342d31355431353a32303a35352d30333a3030&vNF=13,90&vICMS=2,36&digVal=69466b66444662536161626c554539614f35476b4b48342f3964513d&cIdToken=000001&cHashQRCode=41799477BE9E40C0792C3B0E43094EA3CA4A2435
*/
            // o mais correto era fazer um parse, e ver as chaves e talvez um download...
            $tmp_url=parse_url($codigo_barra);
            parse_str($tmp_url['query'],$tmp_query);
            // verifica chNFe
            if(!isset($tmp_query['chNFe']))
                return(false);
            $tmp_chave=$this->_IdentifyChave($tmp_query['chNFe']);
            if($tmp_chave!==false){
                $tmp_query['url']    =$codigo_barra;
                $tmp_query['modelo']    =$tmp_chave;
                if($tmp_query['modelo']=='65'){
                    if($tmp_url['scheme']!='https')    
                        return(false);    // todos webservices do nfce são https, os demais é site pra baixa xml do emitente
                    // verificar o hash do qrcode?
                }
                return($tmp_query);
            }
            return(false);
        }
        function _IdentifyChave($chave){
            $chave_numeros=preg_replace("/[^0-9]/", "", $chave);
            if(strlen($chave_numeros)==44){
                // procura o modelo
                
                
                // nfe, nfce e cte
                //                modelo                digito
                //                |                |
                //                vv                v
                //    00.20.00.00000000000000.00.000.000000000.0.18641952.6
                //    01 23 45 67890123456789 01 234 567890123 4 56789012 3
                if(    substr($chave_numeros,20,2)=='55' ||
                    substr($chave_numeros,20,2)=='57' ||
                    substr($chave_numeros,20,2)=='65'){
                    // verifica digito
                    if(!$this->__calculaDV(substr($chave_numeros,0,43))==substr($chave_numeros,43,1))
                        return(false);
                    return(substr($chave_numeros,20,2));
                }
            }
            return false;
        }
        function __calculaDV($numero) {
            $chave43=str_pad($numero,'0',STR_PAD_LEFT);
            $multiplicadores = array(2,3,4,5,6,7,8,9);
            $i = 42;
            $soma_ponderada=0;
            while ($i >= 0) {
                for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
                    $soma_ponderada+= ((int)substr($chave43,$i,1)) * $multiplicadores[$m];
                    $i--;
                }
            }
            $resto = $soma_ponderada % 11;
            if ($resto == '0' || $resto == '1') {
                $cDV = 0;
            } else {
                $cDV = 11 - $resto;
            }
            return $cDV;
        } //fim __calculaDV

        function extractNFeB2B($parm,$b2b_nfe='b2b'){
            if(is_string($parm)){
                if(@is_file($parm))    // carrega arquivo...
                    $parm=file_get_contents($parm);
            }
            if(!is_object($parm)){    // pode ser passado uma instancia de DomDocument...
                #$dom = new DomDocument;
                $dom = new DOMDocument('1.0', 'utf-8');
                @$dom->loadXML($parm);
                if(!is_object($dom))
                    return false;
            }else
                $dom = $parm;
#die('aki'.get_class($dom));
            if(get_class($dom)!='DOMDocument')
                return false;
            $procnfeProcB2B    = $dom->getElementsByTagName("nfeProcB2B")->item(0);
            if(empty($procnfeProcB2B))
                return false;
            if($b2b_nfe=='b2b'){
                // retorna parte do nfeb2b
                $NFeB2B    = $dom->getElementsByTagName("NFeB2B")->item(0);
                if(empty($NFeB2B))
                    return false;
                // retorna somente o nfeb2b
                $dom2 = new DOMDocument('1.0', 'utf-8');
                $dom2->formatOutput = true;
                $dom2->preserveWhiteSpace = false;
                $tmp_nfeb2b=$dom2->importNode( $NFeB2B ,true);
                #var_dump($tmp_nfeb2b);
                $dom2->appendChild($tmp_nfeb2b);
                return($dom2);
            }
            // retorna parte da nfe
            $nfeProc    = $dom->getElementsByTagName("nfeProc")->item(0);
            if(empty($nfeProc))
                return false;
            $dom2 = new DOMDocument('1.0', 'utf-8');
            $dom2->formatOutput = true;
            $dom2->preserveWhiteSpace = false;
            $tmp_nfe=$dom2->importNode( $nfeProc ,true);
            $dom2->appendChild($tmp_nfe);
            return($dom2);
        }
        function _IdentifyFileTXT($parm){
            // ARQUIVOS TXT
            if(is_string($parm)){
                                $TMP_TXT=substr($parm,0,20);    // diminui o processamento de string grande
                // NOTAFISCAL|1|
                // NOTA FISCAL|1|
                if(strpos($TMP_TXT,'NOTA FISCAL|')===0 || strpos($TMP_TXT,'NOTAFISCAL|')===0)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_TXT_NFE));
                // REGISTROSCTE|1|
                // REGISTROS CTE|1|
                if(strpos($TMP_TXT,'REGISTROSCTE|')===0 || strpos($TMP_TXT,'REGISTROS CTE|')===0)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_TXT_CTE));
                unset($TMP_TXT);
            }
            return false;
        }
        function _IdentifyFileXML($parm){
            // arquivos XML (no futuro retornar a versão do XML, nfe e cte tem versões...)
            if(!is_object($parm)){    // pode ser passado uma instancia de DomDocument...
                #$dom = new DomDocument;
                $dom = new DOMDocument('1.0', 'utf-8');
                @$dom->loadXML($parm);
                if(!is_object($dom))
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
            }else
                $dom = $parm;
#die('aki'.get_class($dom));
            if(get_class($dom)!='DOMDocument')
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                
            // Eventos NFE
            $procEventoNFe    = $dom->getElementsByTagName("procEventoNFe")->item(0);
            if(!empty($procEventoNFe)){
                // verifica se não é nfeb2b
                $procnfeProcB2B    = $dom->getElementsByTagName("nfeProcB2B")->item(0);
                $NFeB2B    = $dom->getElementsByTagName("NFeB2B")->item(0);
                if(!empty($procnfeProcB2B) && !empty($NFeB2B))
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFe_NFeB2B));
                // verifica se não é nfeb2b
                
                // verificar se é nfc-e (mod=65)    // verificar se está ok!
                $chave        = $dom->getElementsByTagName("chNFe")->item(0);
                if(!empty($chave))
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                $chave        =$chave->nodeValue;
                $mod        =substr($chave,20,2);
                if($mod!=55 && $mod!=65)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                if($mod==65)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_EventoNFCe));
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_EventoNFe));    
            }
            // Eventos CTE
            $procEventoCTe    = $dom->getElementsByTagName("procEventoCTe")->item(0);    // verificar se é esta tag realmente
            if(!empty($procEventoCTe)){
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_EventoCTe));
            }
            // CTe
            $protCTe    = $dom->getElementsByTagName("protCTe")->item(0);
            if(!empty($protCTe))
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_CTe));
            
            $CTe        = $dom->getElementsByTagName("CTe")->item(0);
            $infCTe        = $dom->getElementsByTagName("infCTe")->item(0);
            if(!empty($CTe) && !empty($infCTe))
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_CTe_SEM_PROTOCOLO));
            
            $procCancCTe    = $dom->getElementsByTagName("procCancCTe")->item(0);
            $cancCTe    = $dom->getElementsByTagName("cancCTe")->item(0);
            if(!empty($procCancCTe) && !empty($cancCTe))
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_CTe_procCancCTe));
            
            $infInut        = $dom->getElementsByTagName("inutCTe")->item(0);
            $retInutCTe     = $dom->getElementsByTagName("retInutCTe")->item(0);
            if(!empty($infInut) && !empty($retInutCTe))
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_CTe_InutFaixa));
            unset(    $CTe,$infCTe,$protCTe,
                $procCancCTe,$cancCTe,
                $infInut,$retInutCTe);
            
            
            // NFe
            $nfeProc        = $dom->getElementsByTagName("nfeProc")->item(0);
            if(!empty($nfeProc)){
                // verificar se é nfc-e (mod=65)
                $mod        = $nfeProc->getElementsByTagName("mod")->item(0);
                if(empty($mod))
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                $mod        =$mod->nodeValue;
                if($mod!=55 && $mod!=65)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                if($mod==65)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFCe));
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFe));
            }
            
            $NFe        = $dom->getElementsByTagName("NFe")->item(0);
            $infNFe        = $dom->getElementsByTagName("infNFe")->item(0);
            if(!empty($NFe) && !empty($infNFe)){
                // verificar se é nfc-e (mod=65)
                $mod        = $NFe->getElementsByTagName("mod")->item(0);
                if(empty($mod))
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                $mod        =$mod->nodeValue;
                if($mod!=55 && $mod!=65)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                if($mod==65)
                    return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFCe_SEM_PROTOCOLO));    // SERÁ QUE EXISTE?!
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFe_SEM_PROTOCOLO));
            }
            
            $procCancNFe    = $dom->getElementsByTagName("procCancNFe")->item(0);
            $cancNFe    = $dom->getElementsByTagName("cancNFe")->item(0);
            if(!empty($procCancNFe) && !empty($cancNFe))
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFe_procCancNFe));
            
            $infInut        = $dom->getElementsByTagName("inutNFe")->item(0);
            $retInutNFe     = $dom->getElementsByTagName("retInutNFe")->item(0);
            if(!empty($infInut) && !empty($retInutNFe))
                return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_NFe_InutFaixa));
            unset(    $nfeProc,$NFe,$infNFe,
                $procCancNFe, $cancNFe, 
                $infInut, $retInutNFe,
                $dom);
            return false;
        }
        
        // esta função tenta ler uma imagem ou pdf e pegar as chaves quando validas, ou o qrcode quando nfce
        // ela vai criar arquivos temporarios e usar arquivos executaveis externos pela função exec, então fiquem atentos
        function _IdentifyFilePDF_Image($parm,$force_imagem=false){
            if(    !function_exists('glob') || !function_exists('exec') || !function_exists('unlink') || !function_exists('tempnam') ||
                !function_exists('file_get_contents') || !function_exists('file_put_contents') || !function_exists('escapeshellarg'))     // depois dar uma limpada no codigo, removendo a verificação das funções q tem em todos php
                return false;
            if(!is_string($parm) && !is_resource($parm))    // pode ser uma imagem $gd
                return false;
            if(!@is_executable($this->path_zbarimg))
                return false;

            if(is_string($parm)){
                if(@is_file($parm))    // carrega arquivo...
                    $parm=file_get_contents($parm);
                // parm é o conteudo do arquivo e não o local do arquivo
                if(strlen($parm)<256)    // pelomenos 256 bytes.. (verificar o menor possivel para uma imagem util)
                    return false;
            }elseif(is_resource($parm)){
                if(get_resource_type($parm)!='gd')    // tem q ser gd
                    return false;
            }

            
            $path=$this->path_tmp;
            if(!is_dir($path)) 
                $path=dirname(__FILE__);    // tentativa de arrumar um path...
            $path.='/';
            /*
                procura codigos de barra e tenta identificar se existe alguma informação relevante
                esta função pode retornar um array com varios documentos, são separados cada um por um indice [0 .. n] e dentro de cada dele valores especificos
                $ret=array('tipo'=>NFEPHP_TIPO_ARQUIVO_ARRAY,
                    0=>array de retorno1,
                    1=>array de retorno2,
                    2=>array de retorno3,
                    ...
                    );
            */
            $arquivo_tmp='';
            if(!$force_imagem){
                // verifica se é imagem...
                if(!is_resource($parm)){
                    if(function_exists('imagecreatefromstring'))
                        $tmp=@imagecreatefromstring($parm);
                    else
                        $tmp=false;
                }else{
                    $tmp=& $parm;    // referencia o resource
                }
                if($tmp===false){
                    // verifica se é pdf, se for converte pra varias imagens com 300dpi e abre cada um
                    if(!is_executable($this->path_convert))
                        return false;
                    $ret=false;
                    $arquivo_tmp = tempnam( $path , "_IdentifyNFEPHP_convert");
                    file_put_contents($arquivo_tmp,$parm);
                    $cmd=    escapeshellarg($this->path_convert)." ".
                        "-density 300 ".
                        escapeshellarg($arquivo_tmp)." ".
                        escapeshellarg($arquivo_tmp.'.jpg');
#echo "convert=> $cmd\n";
                    exec($cmd);
                    @unlink($arquivo_tmp);
                    // pega todos os arquivo .jpg
                    $GLOB=glob($arquivo_tmp.'*.jpg');
                    if(is_array($GLOB))
                        foreach ($GLOB as $filename) {
#echo "$filename\n";
                            $tmp=$this->_IdentifyFilePDF_Image($filename,true);
                            if($tmp!==false){
                                // achou algo...
                                if($ret===false) $ret=array('tipo'=>NFEPHP_TIPO_ARQUIVO_ARRAY);
                                $ret[]=$tmp;
                            }
                            unlink($filename);
                        }
                    return($ret);
                }
                // grava em um arquivo tmp
                $arquivo_tmp = tempnam( $path , "_IdentifyNFEPHP_zbarimg");
                if($tmp!==false){
                    if(!function_exists('imagejpeg'))
                        imagepng($tmp,$arquivo_tmp,0);        // vai com png sem compressão (0), as vezes não reconhece no zbarimg
                    else
                        imagejpeg($tmp,$arquivo_tmp,100);    // jpg é mais facil de ser reconhecida mas precisa ter alta qualidade
                    imagedestroy($tmp);
                }else{
                    file_put_contents($arquivo_tmp,$parm);
                }
            }else{
                $arquivo_tmp=$parm;
            }
            // aqui temos um arquivo ($arquivo_tmp)
            $cmd=    escapeshellarg($this->path_zbarimg)." ".
                "-D ".
                escapeshellarg($arquivo_tmp);
#echo "zbarimg=> $cmd\n";
            @exec(    $cmd,$output);
            if(!$force_imagem)    @unlink($arquivo_tmp);    // apaga arquivo tmp
#var_dump($output);
            $ret=false;
            foreach($output as $v){
                // pega o tipo e o codigo de barra do retorno do zbarimg (versão testada = 0.10)
                // procura codigos de barra que sejam chave de acesso de CTe ou NFe ou qrcode de NFCe
                $tipo_cod_barra    =substr($v,0,strpos($v,':'));
                $cod_barra    =substr($v,strpos($v,':')+1);
                if($tipo_cod_barra=='CODE-128'){    // CHAVE DE DANFE / DACTE
                    $tmp_chave=$this->_IdentifyChave($cod_barra);
                    if($tmp_chave!==false){
                        if($tmp_chave=='55'){
                            if($ret===false) $ret=array();
                            $ret[]=array(    'chave'    =>$cod_barra,
                                    'tipo'    =>NFEPHP_TIPO_ARQUIVO_PDF_NFE);
                        }elseif($tmp_chave=='57'){
                            if($ret===false) $ret=array();
                            $ret[]=array(    'chave'    =>$cod_barra,
                                    'tipo'    =>NFEPHP_TIPO_ARQUIVO_PDF_CTE);
                        }elseif($tmp_chave=='65'){
                            if($ret===false) $ret=array();
                            $ret[]=array(    'chave'    =>$cod_barra,
                                    'tipo'    =>NFEPHP_TIPO_ARQUIVO_PDF_NFCE);
                        }
                    }
                }elseif($tipo_cod_barra=='QR-Code'){    // CHAVE DE DANFE NFCe
                    // qrcode NFC-e
/*
QR-Code:https://nfce.set.rn.gov.br/consultarNFCe.aspx?chNFe=24130411982113000237650020000000071185945690&nVersao=100&tpAmb=2&dhEmi=323031332d30342d31355431353a32303a35352d30333a3030&vNF=13,90&vICMS=2,36&digVal=69466b66444662536161626c554539614f35476b4b48342f3964513d&cIdToken=000001&cHashQRCode=41799477BE9E40C0792C3B0E43094EA3CA4A2435
*/
                    $tmp_chave=$this->_IdentifyQRCode($cod_barra);
                    if($tmp_chave!==false){
                        if($tmp_chave['modelo']=='65'){
                            $tmp_chave['tipo']=NFEPHP_TIPO_ARQUIVO_PDF_NFCE;
                            $ret[]=$tmp_chave;
                        }elseif($tmp_chave['modelo']=='55'){
                            $tmp_chave['tipo']=NFEPHP_TIPO_ARQUIVO_PDF_NFE;
                            $ret[]=$tmp_chave;
                        }elseif($tmp_chave['modelo']=='57'){
                            $tmp_chave['tipo']=NFEPHP_TIPO_ARQUIVO_PDF_CTE;
                            $ret[]=$tmp_chave;
                        }
                    }
                    unset($tmp_chave);
                }
            }
            if(count($ret)==1)
                return($ret[0]);
            $ret['tipo']=NFEPHP_TIPO_ARQUIVO_ARRAY;        
            return $ret;
        }
        function IdentifyFile($parm){
            if(is_string($parm)){
                if(@is_file($parm))    // carrega arquivo...
                    $parm=file_get_contents($parm);
                // parm é o conteudo do arquivo e não o local do arquivo
            }
            
            // txt de importação de nfe,cte
            $tmp=$this->_IdentifyFileTXT($parm);
            if($tmp!==false){ $tmp['mime']='text/plain'; return($tmp);}
            
            // xml
            $tmp=$this->_IdentifyFileXML($parm);
            if($tmp!==false){ $tmp['mime']='application/xml'; return($tmp);}
            
            // pdf e imagens de danfes,dactes,entre outras imagens 
            $tmp=$this->_IdentifyFilePDF_Image($parm);
            if($tmp!==false){ return($tmp);}
            
            // não deu =(
            return(array('tipo'=>NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
        }
    }
}


// teste
//$v=new IdentifyNFePHP('/usr/local/bin/zbarimg','/usr/bin/convert','/tmp');
//var_dump($v->_IdentifyFilePDF_Image(file_get_contents('../exemplos/dacte.png')));
?>
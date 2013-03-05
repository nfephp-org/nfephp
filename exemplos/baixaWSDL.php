<?php
/**
 * Esta rotina baixa automaticamente os arquivos WSDL das SEFAZ com base 
 * no arquivo nfe_ws2.xml
 * 
 * ATENÇÃO : alguns webservices não retornam no WSDL e outros (ex.PR) retornam o 
 * WSDL com erros internos que devem ser corrigidos.
 * 
 */
error_reporting(E_ALL);ini_set('display_errors', 'On');

$pubKey = '/var/www/nfephp2/certs/<seu cnpj>_pubKEY.pem';
$priKey = '/var/www/nfephp2/certs/<seu cnpj>_priKEY.pem';

$wsFile = '../config/nfe_ws2.xml';
$xml = file_get_contents($wsFile);
//converte o xml em array
$ws = XML2Array::createArray($xml);
//para cada UF
foreach($ws['WS']['UF'] as $uf){
    $sigla = $uf['sigla'];
    $ambiente = array('homologacao','producao');
    //para cada ambiente
    foreach($ambiente as $amb){
        $h = $uf[$amb];
        if (isset($h)){
            foreach($h as $k => $j){
                $nome = $k;
                $url=$j['@value'];
                $metodo=$j['@attributes']['method'];
                $versao = $j['@attributes']['version'];
                if ($url != ''){
                    $aS[] = $sigla;
                    $aA[] = $amb;
                    $aN[] = $nome;
                    $aU[] = $url.'?wsdl';
                    $aM[] = $metodo;
                    $aV[] = $versao;
                }    
            }
        }
    }   
}
//inicia o loop para baixar os arquivos wsdl
$i = 0;
$dir = '../wsdl/2.00/';
foreach($aS as $s){
    $urlsefaz = $aU[$i];
    $fileName = $dir.$aA[$i].'/'.$aS[$i].'_'.$aM[$i].'.asmx';
    //inicia comunicação com curl
    $oCurl = curl_init();
    curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
    curl_setopt($oCurl, CURLOPT_PORT , 443);
    curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
    curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
    curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($oCurl, CURLOPT_SSLCERT, $pubKey);
    curl_setopt($oCurl, CURLOPT_SSLKEY, $priKey);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    $__xml = curl_exec($oCurl);
    $info = curl_getinfo($oCurl);
    curl_close($oCurl);
    //verifica se foi retornado o wsdl
    $n = strpos($__xml,'<wsdl:def');
    if ($n === false){
        //não retornou um wsdl
    } else {
        $wsdl = trim(substr($__xml, $n));
        $wsdl = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".$wsdl;
        file_put_contents($fileName,$wsdl);
        chmod($fileName, 777);
    }    
    $i++;
} //fim do processo


/**
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * See Array2XML: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (07 Dec 2011)
 * Version: 0.2 (04 Mar 2012)
 * 			Fixed typo 'DomDocument' to 'DOMDocument'
 *
 * Usage:
 *       $array = XML2Array::createArray($xml);
 */
class XML2Array {

    private static $xml = null;
    private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        $xml = self::getXMLRoot();
		if(is_string($input_xml)) {
			$parsed = $xml->loadXML($input_xml);
			if(!$parsed) {
				throw new Exception('[XML2Array] Error parsing the XML string.');
			}
		} else {
			if(get_class($input_xml) != 'DOMDocument') {
				throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
			}
			$xml = self::$xml = $input_xml;
		}
		$array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
		$output = array();

		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
				$output['@cdata'] = trim($node->textContent);
				break;

			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;

			case XML_ELEMENT_NODE:

				// for each child node, call the covert function recursively
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = self::convert($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;

						// assume more nodes of same kind are coming
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					} else {
						//check if it is not an empty text node
						if($v !== '') {
							$output = $v;
						}
					}
				}

				if(is_array($output)) {
					// if only one node of its kind, assign it directly instead if array($value);
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1) {
							$output[$t] = $v[0];
						}
					}
					if(empty($output)) {
						//for empty nodes
						$output = '';
					}
				}

				// loop through the attributes and collect them
				if($node->attributes->length) {
					$a = array();
					foreach($node->attributes as $attrName => $attrNode) {
						$a[$attrName] = (string) $attrNode->value;
					}
					// if its an leaf node, store the value in @value instead of directly storing it.
					if(!is_array($output)) {
						$output = array('@value' => $output);
					}
					$output['@attributes'] = $a;
				}
				break;
		}
		return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}
?>

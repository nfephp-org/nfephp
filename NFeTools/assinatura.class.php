<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      assinatura
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * assinatura
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_assinatura {
    private $certificado;

    function __construct() {
        $this->certificado = new certificado();
    }

    /**
     * @param   string XML
     * @param   string tagID
     * @return  mixed (FALSE se erro, senão string XML assinado)
    **/
    function assinaXML($sXML, $tagID) {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->loadXML($sXML);

        $root = $dom->documentElement;
        $node = $dom->getElementsByTagName($tagID)->item(0);

        $Id = trim($node->getAttribute("Id"));

        $idnome = ereg_replace('[^0-9]', '', $Id);

        //extrai os dados da tag para uma string
        $dados = $node->C14N(FALSE, FALSE, NULL, NULL);

        //calcular o hash dos dados
        $hashValue = hash('sha1', $dados, TRUE);

        //converte o valor para base64 para serem colocados no xml
        $digValue = base64_encode($hashValue);

        //monta a tag da assinatura digital
        $Signature = $dom->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'Signature');
        $root->appendChild($Signature);
        $SignedInfo = $dom->createElement('SignedInfo');
        $Signature->appendChild($SignedInfo);

        //Cannocalization
        $newNode = $dom->createElement('CanonicalizationMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');

        //SignatureMethod
        $newNode = $dom->createElement('SignatureMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        //Reference
        $Reference = $dom->createElement('Reference');
        $SignedInfo->appendChild($Reference);
        $Reference->setAttribute('URI', '#'.$Id);

        //Transforms
        $Transforms = $dom->createElement('Transforms');
        $Reference->appendChild($Transforms);

        //Transform
        $newNode = $dom->createElement('Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');

        //Transform
        $newNode = $dom->createElement('Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');

        //DigestMethod
        $newNode = $dom->createElement('DigestMethod');
        $Reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');

        //DigestValue
        $newNode = $dom->createElement('DigestValue', $digValue);
        $Reference->appendChild($newNode);

        // extrai os dados a serem assinados para uma string
        $dados = $SignedInfo->C14N(FALSE, FALSE, NULL, NULL);

        //inicializa a variavel que vai receber a assinatura
        $signature = '';

        //executa a assinatura digital usando o resource da chave privada
        $resp = openssl_sign($dados, $signature, openssl_pkey_get_private($this->certificado->sPrivateKey));

        //codifica assinatura para o padrao base64
        $signatureValue = base64_encode($signature);

        //SignatureValue
        $newNode = $dom->createElement('SignatureValue', $signatureValue);
        $Signature->appendChild($newNode);

        //KeyInfo
        $KeyInfo = $dom->createElement('KeyInfo');
        $Signature->appendChild($KeyInfo);

        //X509Data
        $X509Data = $dom->createElement('X509Data');
        $KeyInfo->appendChild($X509Data);

        //X509Certificate
        $newNode = $dom->createElement('X509Certificate', $this->certificado->sPublicKey);
        $X509Data->appendChild($newNode);

        //grava na string o objeto DOM
        return $dom->saveXML();

    }

}

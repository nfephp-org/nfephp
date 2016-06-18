<?php

namespace NFePHP\Extras;

use \DOMDocument;

class DomDocumentNFePHP extends DOMDocument
{

    /**
     * construtor
     * Executa o construtor-pai do DOMDocument e por padrão define o XML sem espaços
     * e sem identação
     *
     * @param  string $sXml Conteúdo XML opcional a ser carregado no DOM Document.
     * @return void
     */
    public function __construct($sXml = null)
    {
        parent::__construct('1.0', 'utf-8');
        $this->formatOutput = false;
        $this->preserveWhiteSpace = false;
        
        if (is_string($sXml)) {
            $this->loadXML($sXml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
    }
}

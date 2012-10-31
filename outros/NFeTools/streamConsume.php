<?php

/**
 * Alternativa para consumir web services com stream_context
 * Exemplo basico de implementação sem tratamento de erros
 * 
 * @link http://www.php.net/manual/en/function.stream-context-create.php  documentação função
 * @author Luis
 */


//namespace NFe\NfeTools\Consumers;
class streamConsume {

    private $cert, $response, $header, $content, $params;

    /**
     * que feia essa class ;X
     * 
     * @param array $params urlService, method, namespace
     */
    function __construct(Array $params) {
        $this->params = $params;
    }

    public function getResponse() {
        return $this->response;
    }
//certKey.pem full path
    public function setCert($cert) {
        $this->cert = $cert;
        return $this;
    }

    public function setHeader($header) {
        $this->header = (string)$header;
        return $this;
    }

    public function setContent($content) {
        $this->content = (string)$content;
        return $this;
    }

    public function sendRequest() {

        $data = '';
        $data .= '<?xml version="1.0" encoding="utf-8"?>';
        $data .= '<soap12:Envelope ';
        $data .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $data .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
        $data .= 'xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">';
        $data .= '<soap12:Header>';
        $data .= $this->header;
        $data .= '</soap12:Header>';
        $data .= '<soap12:Body>';
        $data .= $this->content;
        $data .= '</soap12:Body>';
        $data .= '</soap12:Envelope>';
        $tamanho = strlen($data);

        $parametros = Array(
            'Content-Type: application/soap+xml;charset=utf-8;action="' . $this->params['namespace'] . "/" . $this->params['method'] . '"', 'SOAPAction: "' . $this->params['method'] . '"'
            , "Content-length: $tamanho");

        $opts = array(
            'http' => array(
                'method' => "POST",
                'request_fulluri' => True,
                'header' => $parametros,
                'content' => $data
            ), 'ssl' => array(
                'local_cert' => $this->cert) 
        );
        $this->response = @file_get_contents($this->params['urlService'], null, stream_context_create($opts));
        if (!$this->response)
            throw new \RuntimeException(implode(', ', error_get_last()));
    }

}


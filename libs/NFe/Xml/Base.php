<?php

//namespace NFe\Xml;

//use \DOMDocument;
//use \Exception;

class Base
{
    public $dom; //DOMDOcument
    public $erros = array(); //Array
    
    /**
     * __contruct
     * Função construtora cria um objeto DOMDocument
     * que será carregado com a NFe
     * 
     * @return none
     */
    public function __construct($dom = '')
    {
        if (!empty($dom)) {
            $this->dom = $dom;
            return;
        }
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->preserveWhiteSpace = false;
    }
     

   /**
    * zAddChild
    * Adiciona um elemento ao node xml passado como referencia
    * Serão inclusos erros na array $erros[] sempre que a tag for obrigatória e
    * nenhum parâmetro for passado na variável $content e $force for false
    * @param DOMElement $parent
    * @param string $name
    * @param string $content
    * @param boolean $obrigatorio
    * @param string $descricao
    * @param boolean $force força a criação do elemento mesmo sem dados e não considera como erro
    * @return void
    */
    private function zAddChild(&$parent, $name, $content = '', $obrigatorio = false, $descricao = "", $force = false)
    {
        if ($obrigatorio && $content === '' && !$force) {
            $this->erros[] = array(
                "tag" => $name,
                "desc" => $descricao,
                "erro" => "Preenchimento Obrigatório!"
            );
        }
        if ($obrigatorio || $content !== '') {
            $content = trim($content);
            $temp = $this->dom->createElement($name, $content);
            $parent->appendChild($temp);
        }
    }
    
    /**
     * zAppChild
     * Acrescenta DOMElement a pai DOMElement
     * Caso o pai esteja vazio retorna uma exception com a mensagem
     * O parametro "child" pode ser vazio
     * @param DOMElement $parent
     * @param DOMElement $child
     * @param string $mensagem
     * @return void
     * @throws Exception
     */
    private function zAppChild(&$parent, $child, $mensagem = '')
    {
        if (empty($parent)) {
            throw new Exception($mensagem);
        }
        if (!empty($child)) {
            $parent->appendChild($child);
        }
    }
}

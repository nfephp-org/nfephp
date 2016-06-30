<?php

namespace NFePHP\Extras;

interface DocumentoNFePHP
{
    public function monta($orientacao = '', $papel = 'A4', $logoAlign = 'C');
    public function printDocument($nome = '', $destino = 'I', $printer = '');
    public function simpleConsistencyCheck();
}

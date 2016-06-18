<?php

namespace NFePHP\Extras;

class CommonNFePHP
{
    /**
     * pAdicionaLogoPeloCnpj
     *
     * @param  none
     * @return none
     */
    protected function pAdicionaLogoPeloCnpj()
    {
        if (!isset($this->logomarca)) {
            return;
        }
        if ($this->logomarca != '') {
            return;
        }
        if (!isset($this->emit)) {
            return;
        }
        //se não foi passado o caminho para o logo procurar diretorio abaixo
        $imgPath = "logos/" . $this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue . ".jpg";
        if (file_exists($imgPath)) {
            $this->logomarca = $imgPath;
            return;
        }
        //procurar diretorio acima do anterior
        $imgPath = "../" . $imgPath;
        if (file_exists($imgPath)) {
            $this->logomarca = $imgPath;
            return;
        }
        //procurar diretorio acima do anterior
        $imgPath = "../" . $imgPath;
        if (file_exists($imgPath)) {
            $this->logomarca = $imgPath;
            return;
        }
        //procurar diretorio acima do anterior
        $imgPath = "../" . $imgPath;
        if (file_exists($imgPath)) {
            $this->logomarca = $imgPath;
            return;
        }
    }

    /**
     * pSimpleGetValue
     * Extrai o valor do node DOM
     *
     * @param  object                                        $theObj          Instancia de DOMDocument ou DOMElement
     * @param  string                                        $keyName         identificador da TAG do xml
     * @param  string                                        $extraTextBefore prefixo do retorno
     * @param  string extraTextAfter sufixo do retorno
     * @param  number itemNum numero do item a ser retornado
     * @return string
     */
    protected function pSimpleGetValue($theObj, $keyName, $extraTextBefore = '', $extraTextAfter = '', $itemNum = 0)
    {
        if (empty($theObj)) {
            return '';
        }
        
        //if (!($theObj instanceof DOMDocument) && !($theObj instanceof DOMElement)) {
        //    throw new nfephpException(
        //        "Metodo CommonNFePHP::pSimpleGetValue() "
        //        . "com parametro do objeto invalido, verifique!"
        //    );
        //}
        
        $vct = $theObj->getElementsByTagName($keyName)->item($itemNum);
        if (isset($vct)) {
            return $extraTextBefore . trim($vct->nodeValue) . $extraTextAfter;
        }
        return '';
    }

    /**
     * pSimpleGetDate
     * Recupera e reformata a data do padrão da NFe para dd/mm/aaaa
     *
     * @author Marcos Diez
     * @param  DOM    $theObj
     * @param  string $keyName   identificador da TAG do xml
     * @param  string $extraText prefixo do retorno
     * @return string
     */
    protected function pSimpleGetDate($theObj, $keyName, $extraText = '')
    {
        if (!isset($theObj) || !is_object($theObj)) {
            return '';
        }
        $vct = $theObj->getElementsByTagName($keyName)->item(0);
        if (isset($vct)) {
            $theDate = explode("-", $vct->nodeValue);
            return $extraText . $theDate[2] . "/" . $theDate[1] . "/" . $theDate[0];
        }
        return '';
    } //fim pSimpleGetDate

    /**
     * pModulo11
     *
     * @param  string $numero
     * @return integer modulo11 do numero passado
     */
    protected function pModulo11($numero = '')
    {
        if ($numero == '') {
            return '';
        }
        $numero = (string) $numero;
        $tamanho = strlen($numero);
        $soma = 0;
        $mult = 2;
        for ($i = $tamanho-1; $i >= 0; $i--) {
            $digito = (int) $numero[$i];
            $r = $digito * $mult;
            $soma += $r;
            $mult++;
            if ($mult == 10) {
                $mult = 2;
            }
        }
        $resto = ($soma * 10) % 11;
        return ($resto == 10 || $resto == 0) ? 1 : $resto;
    }

    /**
     * pYmd2dmy
     * Converte datas no formato YMD (ex. 2009-11-02) para o formato brasileiro 02/11/2009)
     *
     * @param  string $data Parâmetro extraido da NFe
     * @return string Formatada para apresentação da data no padrão brasileiro
     */
    protected function pYmd2dmy($data = '')
    {
        if ($data == '') {
            return '';
        }
        $needle = "/";
        if (strstr($data, "-")) {
            $needle = "-";
        }
        $dt = explode($needle, $data);
        return "$dt[2]/$dt[1]/$dt[0]";
    }

    /**
     * pConvertTime
     * Converte a informação de data e tempo contida na NFe
     *
     * @param  string $DH Informação de data e tempo extraida da NFe
     * @return timestamp UNIX Para uso com a funçao date do php
     */
    protected function pConvertTime($DH = '')
    {
        if ($DH == '') {
            return '';
        }
        $DH = str_replace('+', '-', $DH);
        $aDH = explode('T', $DH);
        $adDH = explode('-', $aDH[0]);
        if (count($aDH) > 1) {
            $inter = explode('-', $aDH[1]);
            $atDH = explode(':', $inter[0]);
            $timestampDH = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
        } else {
            $timestampDH = mktime($month = $adDH[1], $day =  $adDH[2], $year = $adDH[0]);
        }
        return $timestampDH;
    }

    /**
     * pFormat
     * Função de formatação de strings onde o cerquilha # é um coringa
     * que será substituido por digitos contidos em campo.
     *
     * @param  string $campo   String a ser formatada
     * @param  string $mascara Regra de formatção da string (ex. ##.###.###/####-##)
     * @return string Retorna o campo formatado
     */
    protected function pFormat($campo = '', $mascara = '')
    {
        if ($campo == '' || $mascara == '') {
            return $campo;
        }
        //remove qualquer formatação que ainda exista
        $sLimpo = preg_replace("(/[' '-./ t]/)", '', $campo);
        // pega o tamanho da string e da mascara
        $tCampo = strlen($sLimpo);
        $tMask = strlen($mascara);
        if ($tCampo > $tMask) {
            $tMaior = $tCampo;
        } else {
            $tMaior = $tMask;
        }
        //contar o numero de cerquilhas da mascara
        $aMask = str_split($mascara);
        $z=0;
        $flag=false;
        foreach ($aMask as $letra) {
            if ($letra == '#') {
                $z++;
            }
        }
        if ($z > $tCampo) {
            //o campo é menor que esperado
            $flag=true;
        }
        //cria uma variável grande o suficiente para conter os dados
        $sRetorno = '';
        $sRetorno = str_pad($sRetorno, $tCampo+$tMask, " ", STR_PAD_LEFT);
        //pega o tamanho da string de retorno
        $tRetorno = strlen($sRetorno);
        //se houve entrada de dados
        if ($sLimpo != '' && $mascara !='') {
            //inicia com a posição do ultimo digito da mascara
            $x = $tMask;
            $y = $tCampo;
            $cI = 0;
            for ($i = $tMaior-1; $i >= 0; $i--) {
                if ($cI < $z) {
                    // e o digito da mascara é # trocar pelo digito do campo
                    // se o inicio da string da mascara for atingido antes de terminar
                    // o campo considerar #
                    if ($x > 0) {
                        $digMask = $mascara[--$x];
                    } else {
                        $digMask = '#';
                    }
                    //se o fim do campo for atingido antes do fim da mascara
                    //verificar se é ( se não for não use
                    if ($digMask=='#') {
                        $cI++;
                        if ($y > 0) {
                            $sRetorno[--$tRetorno] = $sLimpo[--$y];
                        } else {
                            //$sRetorno[--$tRetorno] = '';
                        }
                    } else {
                        if ($y > 0) {
                            $sRetorno[--$tRetorno] = $mascara[$x];
                        } else {
                            if ($mascara[$x] =='(') {
                                $sRetorno[--$tRetorno] = $mascara[$x];
                            }
                        }
                        $i++;
                    }
                }
            }
            if (!$flag) {
                if ($mascara[0] != '#') {
                    $sRetorno = '(' . trim($sRetorno);
                }
            }
            return trim($sRetorno);
        } else {
            return '';
        }
    }

    /**
     * pGetNumLines
     * Obtem o numero de linhas usadas pelo texto usando a fonte especifidada
     *
     * @param  string $text
     * @param  number $width
     * @param  array  $aFont
     * @return number numero de linhas
     */
    protected function pGetNumLines($text, $width, $aFont = array('font'=>'Times','size'=>8,'style'=>''))
    {
        $text = trim($text);
        $this->pdf->SetFont($aFont['font'], $aFont['style'], $aFont['size']);
        $n = $this->pdf->WordWrap($text, $width-0.2);
        return $n;
    }


    /**
     * pTextBox
     * Cria uma caixa de texto com ou sem bordas. Esta função perimite o alinhamento horizontal
     * ou vertical do texto dentro da caixa.
     * Atenção : Esta função é dependente de outras classes de FPDF
     * Ex. $this->pTextBox(2,20,34,8,'Texto',array('fonte'=>$this->fontePadrao,
     * 'size'=>10,'style='B'),'C','L',FALSE,'http://www.nfephp.org')
     *
     * @param  number  $x       Posição horizontal da caixa, canto esquerdo superior
     * @param  number  $y       Posição vertical da caixa, canto esquerdo superior
     * @param  number  $w       Largura da caixa
     * @param  number  $h       Altura da caixa
     * @param  string  $text    Conteúdo da caixa
     * @param  array   $aFont   Matriz com as informações para formatação do texto com fonte, tamanho e estilo
     * @param  string  $vAlign  Alinhamento vertical do texto, T-topo C-centro B-base
     * @param  string  $hAlign  Alinhamento horizontal do texto, L-esquerda, C-centro, R-direita
     * @param  boolean $border  TRUE ou 1 desenha a borda, FALSE ou 0 Sem borda
     * @param  string  $link    Insere um hiperlink
     * @param  boolean $force   Se for true força a caixa com uma unica linha e para isso atera o tamanho do fonte até caber no espaço, se falso mantem o tamanho do fonte e usa quantas linhas forem necessárias
     * e para isso atera o tamanho do fonte até caber no espaço,
     * se falso mantem o tamanho do fonte e usa quantas linhas forem necessárias
     * @param  number  $hmax
     * @param  number  $vOffSet incremento forçado na na posição Y
     * @return number $height Qual a altura necessária para desenhar esta textBox
     */
    protected function pTextBox(
        $x,
        $y,
        $w,
        $h,
        $text = '',
        $aFont = array('font'=>'Times','size'=>8,'style'=>''),
        $vAlign = 'T',
        $hAlign = 'L',
        $border = 1,
        $link = '',
        $force = true,
        $hmax = 0,
        $vOffSet = 0
    ) {
        $oldY = $y;
        $temObs = false;
        $resetou = false;
        if ($w < 0) {
            return $y;
        }
        if (is_object($text)) {
            $text = '';
        }
        if (is_string($text)) {
            //remover espaços desnecessários
            $text = trim($text);
            //converter o charset para o fpdf
            $text = utf8_decode($text);
        } else {
            $text = (string) $text;
        }
        //desenhar a borda da caixa
        if ($border) {
            $this->pdf->RoundedRect($x, $y, $w, $h, 0.8, '1234', 'D');
        }
        //estabelecer o fonte
        $this->pdf->SetFont($aFont['font'], $aFont['style'], $aFont['size']);
        //calcular o incremento
        $incY = $this->pdf->FontSize; //tamanho da fonte na unidade definida
        if (!$force) {
            //verificar se o texto cabe no espaço
            $n = $this->pdf->WordWrap($text, $w);
        } else {
            $n = 1;
        }
        //calcular a altura do conjunto de texto
        $altText = $incY * $n;
        //separar o texto em linhas
        $lines = explode("\n", $text);
        //verificar o alinhamento vertical
        if ($vAlign == 'T') {
            //alinhado ao topo
            $y1 = $y+$incY;
        }
        if ($vAlign == 'C') {
            //alinhado ao centro
            $y1 = $y + $incY + (($h-$altText)/2);
        }
        if ($vAlign == 'B') {
            //alinhado a base
            $y1 = ($y + $h)-0.5;
        }
        //para cada linha
        foreach ($lines as $line) {
            //verificar o comprimento da frase
            $texto = trim($line);
            $comp = $this->pdf->GetStringWidth($texto);
            if ($force) {
                $newSize = $aFont['size'];
                while ($comp > $w) {
                    //estabelecer novo fonte
                    $this->pdf->SetFont($aFont['font'], $aFont['style'], --$newSize);
                    $comp = $this->pdf->GetStringWidth($texto);
                }
            }
            //ajustar ao alinhamento horizontal
            if ($hAlign == 'L') {
                $x1 = $x+0.5;
            }
            if ($hAlign == 'C') {
                $x1 = $x + (($w - $comp)/2);
            }
            if ($hAlign == 'R') {
                $x1 = $x + $w - ($comp+0.5);
            }
            //escrever o texto
            if ($vOffSet > 0) {
                if ($y1 > ($oldY+$vOffSet)) {
                    if (!$resetou) {
                        $y1 = $oldY;
                        $resetou = true;
                    }
                    $this->pdf->Text($x1, $y1, $texto);
                }
            } else {
                $this->pdf->Text($x1, $y1, $texto);
            }
            //incrementar para escrever o proximo
            $y1 += $incY;
            if (($hmax > 0) && ($y1 > ($y+($hmax-1)))) {
                $temObs = true;
                break;
            }
        }
        return ($y1-$y)-$incY;
    } // fim função __textBox

    /**
     * pTextBox90
     * Cria uma caixa de texto com ou sem bordas. Esta função permite o alinhamento horizontal
     * ou vertical do texto dentro da caixa, rotacionando-o em 90 graus, essa função precisa que
     * a classe PDF contenha a função Rotate($angle,$x,$y);
     * Atenção : Esta função é dependente de outras classes de FPDF
     * Ex. $this->__textBox90(2,20,34,8,'Texto',array('fonte'=>$this->fontePadrao,
     * 'size'=>10,'style='B'),'C','L',FALSE,'http://www.nfephp.org')
     *
     * @param  number  $x       Posição horizontal da caixa, canto esquerdo superior
     * @param  number  $y       Posição vertical da caixa, canto esquerdo superior
     * @param  number  $w       Largura da caixa
     * @param  number  $h       Altura da caixa
     * @param  string  $text    Conteúdo da caixa
     * @param  array   $aFont   Matriz com as informações para formatação do texto com fonte, tamanho e estilo
     * @param  string  $vAlign  Alinhamento vertical do texto, T-topo C-centro B-base
     * @param  string  $hAlign  Alinhamento horizontal do texto, L-esquerda, C-centro, R-direita
     * @param  boolean $border  TRUE ou 1 desenha a borda, FALSE ou 0 Sem borda
     * @param  string  $link    Insere um hiperlink
     * @param  boolean $force   Se for true força a caixa com uma unica linha e para isso atera o tamanho do fonte até caber no espaço, se falso mantem o tamanho do fonte e usa quantas linhas forem necessárias
     * linha e para isso atera o tamanho do fonte até caber no espaço,
     * se falso mantem o tamanho do fonte e usa quantas linhas forem necessárias
     * @param  number  $hmax
     * @param  number  $vOffSet incremento forçado na na posição Y
     * @return number $height Qual a altura necessária para desenhar esta textBox
     */
    protected function pTextBox90(
        $x,
        $y,
        $w,
        $h,
        $text = '',
        $aFont = array('font'=>'Times','size'=>8,'style'=>''),
        $vAlign = 'T',
        $hAlign = 'L',
        $border = 1,
        $link = '',
        $force = true,
        $hmax = 0,
        $vOffSet = 0
    ) {
        //Rotacionado
        $this->pdf->Rotate(90, $x, $y);
        $oldY = $y;
        $temObs = false;
        $resetou = false;
        if ($w < 0) {
            return $y;
        }
        if (is_object($text)) {
            $text = '';
        }
        if (is_string($text)) {
            //remover espaços desnecessários
            $text = trim($text);
            //converter o charset para o fpdf
            $text = utf8_decode($text);
        } else {
            $text = (string) $text;
        }
        //desenhar a borda da caixa
        if ($border) {
            $this->pdf->RoundedRect($x, $y, $w, $h, 0.8, '1234', 'D');
        }
        //estabelecer o fonte
        $this->pdf->SetFont($aFont['font'], $aFont['style'], $aFont['size']);
        //calcular o incremento
        $incY = $this->pdf->FontSize; //tamanho da fonte na unidade definida
        if (!$force) {
            //verificar se o texto cabe no espaço
            $n = $this->pdf->WordWrap($text, $w);
        } else {
            $n = 1;
        }
        //calcular a altura do conjunto de texto
        $altText = $incY * $n;
        //separar o texto em linhas
        $lines = explode("\n", $text);
        //verificar o alinhamento vertical
        if ($vAlign == 'T') {
            //alinhado ao topo
            $y1 = $y+$incY;
        }
        if ($vAlign == 'C') {
            //alinhado ao centro
            $y1 = $y + $incY + (($h-$altText)/2);
        }
        if ($vAlign == 'B') {
            //alinhado a base
            $y1 = ($y + $h)-0.5;
        }
        //para cada linha
        foreach ($lines as $line) {
            //verificar o comprimento da frase
            $texto = trim($line);
            $comp = $this->pdf->GetStringWidth($texto);
            if ($force) {
                $newSize = $aFont['size'];
                while ($comp > $w) {
                    //estabelecer novo fonte
                    $this->pdf->SetFont($aFont['font'], $aFont['style'], --$newSize);
                    $comp = $this->pdf->GetStringWidth($texto);
                }
            }
            //ajustar ao alinhamento horizontal
            if ($hAlign == 'L') {
                $x1 = $x+0.5;
            }
            if ($hAlign == 'C') {
                $x1 = $x + (($w - $comp)/2);
            }
            if ($hAlign == 'R') {
                $x1 = $x + $w - ($comp+0.5);
            }
            //escrever o texto
            if ($vOffSet > 0) {
                if ($y1 > ($oldY+$vOffSet)) {
                    if (!$resetou) {
                        $y1 = $oldY;
                        $resetou = true;
                    }
                    $this->pdf->Text($x1, $y1, $texto);
                }
            } else {
                $this->pdf->Text($x1, $y1, $texto);
            }
            //incrementar para escrever o proximo
            $y1 += $incY;
            if (($hmax > 0) && ($y1 > ($y+($hmax-1)))) {
                $temObs = true;
                break;
            }
        }
        //Zerando rotação
        $this->pdf->Rotate(0, $x, $y);
        return ($y1-$y)-$incY;
    }
}

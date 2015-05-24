<?php

namespace NFePHP\Extras;

use NFePHP\Extras\FPDF\FPDF;

class PdfNFePHP extends FPDF {
    
    private $T128;                                             // tabela de codigos 128
    private $ABCset="";                                        // conjunto de caracteres legiveis em 128
    private $Aset="";                                          // grupo A do conjunto de de caracteres legiveis
    private $Bset="";                                          // grupo B do conjunto de caracteres legiveis
    private $Cset="";                                          // grupo C do conjunto de caracteres legiveis
    private $SetFrom;                                          // converter de
    private $SetTo;                                            // converter para
    private $JStart = array("A"=>103, "B"=>104, "C"=>105);     // Caracteres de seleção do grupo 128
    private $JSwap = array("A"=>101, "B"=>100, "C"=>99);       // Caracteres de troca de grupo

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        //passar parametros para a classe principal
        parent::FPDF($orientation, $unit, $format);
        // composição dos caracteres do barcode 128
        $this->T128[] = array(2, 1, 2, 2, 2, 2);           //0 : [ ]
        $this->T128[] = array(2, 2, 2, 1, 2, 2);           //1 : [!]
        $this->T128[] = array(2, 2, 2, 2, 2, 1);           //2 : ["]
        $this->T128[] = array(1, 2, 1, 2, 2, 3);           //3 : [#]
        $this->T128[] = array(1, 2, 1, 3, 2, 2);           //4 : [$]
        $this->T128[] = array(1, 3, 1, 2, 2, 2);           //5 : [%]
        $this->T128[] = array(1, 2, 2, 2, 1, 3);           //6 : [&]
        $this->T128[] = array(1, 2, 2, 3, 1, 2);           //7 : [']
        $this->T128[] = array(1, 3, 2, 2, 1, 2);           //8 : [(]
        $this->T128[] = array(2, 2, 1, 2, 1, 3);           //9 : [)]
        $this->T128[] = array(2, 2, 1, 3, 1, 2);           //10 : [*]
        $this->T128[] = array(2, 3, 1, 2, 1, 2);           //11 : [+]
        $this->T128[] = array(1, 1, 2, 2, 3, 2);           //12 : [,]
        $this->T128[] = array(1, 2, 2, 1, 3, 2);           //13 : [-]
        $this->T128[] = array(1, 2, 2, 2, 3, 1);           //14 : [.]
        $this->T128[] = array(1, 1, 3, 2, 2, 2);           //15 : [/]
        $this->T128[] = array(1, 2, 3, 1, 2, 2);           //16 : [0]
        $this->T128[] = array(1, 2, 3, 2, 2, 1);           //17 : [1]
        $this->T128[] = array(2, 2, 3, 2, 1, 1);           //18 : [2]
        $this->T128[] = array(2, 2, 1, 1, 3, 2);           //19 : [3]
        $this->T128[] = array(2, 2, 1, 2, 3, 1);           //20 : [4]
        $this->T128[] = array(2, 1, 3, 2, 1, 2);           //21 : [5]
        $this->T128[] = array(2, 2, 3, 1, 1, 2);           //22 : [6]
        $this->T128[] = array(3, 1, 2, 1, 3, 1);           //23 : [7]
        $this->T128[] = array(3, 1, 1, 2, 2, 2);           //24 : [8]
        $this->T128[] = array(3, 2, 1, 1, 2, 2);           //25 : [9]
        $this->T128[] = array(3, 2, 1, 2, 2, 1);           //26 : [:]
        $this->T128[] = array(3, 1, 2, 2, 1, 2);           //27 : [;]
        $this->T128[] = array(3, 2, 2, 1, 1, 2);           //28 : [<]
        $this->T128[] = array(3, 2, 2, 2, 1, 1);           //29 : [=]
        $this->T128[] = array(2, 1, 2, 1, 2, 3);           //30 : [>]
        $this->T128[] = array(2, 1, 2, 3, 2, 1);           //31 : [?]
        $this->T128[] = array(2, 3, 2, 1, 2, 1);           //32 : [@]
        $this->T128[] = array(1, 1, 1, 3, 2, 3);           //33 : [A]
        $this->T128[] = array(1, 3, 1, 1, 2, 3);           //34 : [B]
        $this->T128[] = array(1, 3, 1, 3, 2, 1);           //35 : [C]
        $this->T128[] = array(1, 1, 2, 3, 1, 3);           //36 : [D]
        $this->T128[] = array(1, 3, 2, 1, 1, 3);           //37 : [E]
        $this->T128[] = array(1, 3, 2, 3, 1, 1);           //38 : [F]
        $this->T128[] = array(2, 1, 1, 3, 1, 3);           //39 : [G]
        $this->T128[] = array(2, 3, 1, 1, 1, 3);           //40 : [H]
        $this->T128[] = array(2, 3, 1, 3, 1, 1);           //41 : [I]
        $this->T128[] = array(1, 1, 2, 1, 3, 3);           //42 : [J]
        $this->T128[] = array(1, 1, 2, 3, 3, 1);           //43 : [K]
        $this->T128[] = array(1, 3, 2, 1, 3, 1);           //44 : [L]
        $this->T128[] = array(1, 1, 3, 1, 2, 3);           //45 : [M]
        $this->T128[] = array(1, 1, 3, 3, 2, 1);           //46 : [N]
        $this->T128[] = array(1, 3, 3, 1, 2, 1);           //47 : [O]
        $this->T128[] = array(3, 1, 3, 1, 2, 1);           //48 : [P]
        $this->T128[] = array(2, 1, 1, 3, 3, 1);           //49 : [Q]
        $this->T128[] = array(2, 3, 1, 1, 3, 1);           //50 : [R]
        $this->T128[] = array(2, 1, 3, 1, 1, 3);           //51 : [S]
        $this->T128[] = array(2, 1, 3, 3, 1, 1);           //52 : [T]
        $this->T128[] = array(2, 1, 3, 1, 3, 1);           //53 : [U]
        $this->T128[] = array(3, 1, 1, 1, 2, 3);           //54 : [V]
        $this->T128[] = array(3, 1, 1, 3, 2, 1);           //55 : [W]
        $this->T128[] = array(3, 3, 1, 1, 2, 1);           //56 : [X]
        $this->T128[] = array(3, 1, 2, 1, 1, 3);           //57 : [Y]
        $this->T128[] = array(3, 1, 2, 3, 1, 1);           //58 : [Z]
        $this->T128[] = array(3, 3, 2, 1, 1, 1);           //59 : [[]
        $this->T128[] = array(3, 1, 4, 1, 1, 1);           //60 : [\]
        $this->T128[] = array(2, 2, 1, 4, 1, 1);           //61 : []]
        $this->T128[] = array(4, 3, 1, 1, 1, 1);           //62 : [^]
        $this->T128[] = array(1, 1, 1, 2, 2, 4);           //63 : [_]
        $this->T128[] = array(1, 1, 1, 4, 2, 2);           //64 : [`]
        $this->T128[] = array(1, 2, 1, 1, 2, 4);           //65 : [a]
        $this->T128[] = array(1, 2, 1, 4, 2, 1);           //66 : [b]
        $this->T128[] = array(1, 4, 1, 1, 2, 2);           //67 : [c]
        $this->T128[] = array(1, 4, 1, 2, 2, 1);           //68 : [d]
        $this->T128[] = array(1, 1, 2, 2, 1, 4);           //69 : [e]
        $this->T128[] = array(1, 1, 2, 4, 1, 2);           //70 : [f]
        $this->T128[] = array(1, 2, 2, 1, 1, 4);           //71 : [g]
        $this->T128[] = array(1, 2, 2, 4, 1, 1);           //72 : [h]
        $this->T128[] = array(1, 4, 2, 1, 1, 2);           //73 : [i]
        $this->T128[] = array(1, 4, 2, 2, 1, 1);           //74 : [j]
        $this->T128[] = array(2, 4, 1, 2, 1, 1);           //75 : [k]
        $this->T128[] = array(2, 2, 1, 1, 1, 4);           //76 : [l]
        $this->T128[] = array(4, 1, 3, 1, 1, 1);           //77 : [m]
        $this->T128[] = array(2, 4, 1, 1, 1, 2);           //78 : [n]
        $this->T128[] = array(1, 3, 4, 1, 1, 1);           //79 : [o]
        $this->T128[] = array(1, 1, 1, 2, 4, 2);           //80 : [p]
        $this->T128[] = array(1, 2, 1, 1, 4, 2);           //81 : [q]
        $this->T128[] = array(1, 2, 1, 2, 4, 1);           //82 : [r]
        $this->T128[] = array(1, 1, 4, 2, 1, 2);           //83 : [s]
        $this->T128[] = array(1, 2, 4, 1, 1, 2);           //84 : [t]
        $this->T128[] = array(1, 2, 4, 2, 1, 1);           //85 : [u]
        $this->T128[] = array(4, 1, 1, 2, 1, 2);           //86 : [v]
        $this->T128[] = array(4, 2, 1, 1, 1, 2);           //87 : [w]
        $this->T128[] = array(4, 2, 1, 2, 1, 1);           //88 : [x]
        $this->T128[] = array(2, 1, 2, 1, 4, 1);           //89 : [y]
        $this->T128[] = array(2, 1, 4, 1, 2, 1);           //90 : [z]
        $this->T128[] = array(4, 1, 2, 1, 2, 1);           //91 : [{]
        $this->T128[] = array(1, 1, 1, 1, 4, 3);           //92 : [|]
        $this->T128[] = array(1, 1, 1, 3, 4, 1);           //93 : [}]
        $this->T128[] = array(1, 3, 1, 1, 4, 1);           //94 : [~]
        $this->T128[] = array(1, 1, 4, 1, 1, 3);           //95 : [DEL]
        $this->T128[] = array(1, 1, 4, 3, 1, 1);           //96 : [FNC3]
        $this->T128[] = array(4, 1, 1, 1, 1, 3);           //97 : [FNC2]
        $this->T128[] = array(4, 1, 1, 3, 1, 1);           //98 : [SHIFT]
        $this->T128[] = array(1, 1, 3, 1, 4, 1);           //99 : [Cswap]
        $this->T128[] = array(1, 1, 4, 1, 3, 1);           //100 : [Bswap]
        $this->T128[] = array(3, 1, 1, 1, 4, 1);           //101 : [Aswap]
        $this->T128[] = array(4, 1, 1, 1, 3, 1);           //102 : [FNC1]
        $this->T128[] = array(2, 1, 1, 4, 1, 2);           //103 : [Astart]
        $this->T128[] = array(2, 1, 1, 2, 1, 4);           //104 : [Bstart]
        $this->T128[] = array(2, 1, 1, 2, 3, 2);           //105 : [Cstart]
        $this->T128[] = array(2, 3, 3, 1, 1, 1);           //106 : [STOP]
        $this->T128[] = array(2, 1);                       //107 : [END BAR]
        for ($i = 32; $i <= 95; $i++) {   // conjunto de caracteres
            $this->ABCset .= chr($i);
        }
        $this->Aset = $this->ABCset;
        $this->Bset = $this->ABCset;
        for ($i = 0; $i <= 31; $i++) {
            $this->ABCset .= chr($i);
            $this->Aset .= chr($i);
        }
        for ($i = 96; $i <= 126; $i++) {
            $this->ABCset .= chr($i);
            $this->Bset .= chr($i);
        }
        $this->Cset="0123456789";
        for ($i = 0; $i < 96; $i++) {
            // convertendo grupos A & B
            if (isset($this->SetFrom["A"])) {
                $this->SetFrom["A"] .= chr($i);
            }
            if (isset($this->SetFrom["B"])) {
                $this->SetFrom["B"] .= chr($i + 32);
            }
            if (isset($this->SetTo["A"])) {
                $this->SetTo["A"] .= chr(($i < 32) ? $i+64 : $i-32);
            }
            if (isset($this->SetTo["A"])) {
                $this->SetTo["B"] .= chr($i);
            }
        }
    }//fim __construct

    /**
     * Code128
     * Imprime barcode 128
     * @package     FPDF
     * @name        Code128
     * @version     1.0
     * @author      Roland Gautier
     */
    public function Code128($x, $y, $code, $w, $h)
    {
        $Aguid="";
        $Bguid="";
        $Cguid="";
        for ($i=0; $i < strlen($code); $i++) {
            $needle=substr($code, $i, 1);
            $Aguid .= ((strpos($this->Aset, $needle)===false) ? "N" : "O");
            $Bguid .= ((strpos($this->Bset, $needle)===false) ? "N" : "O");
            $Cguid .= ((strpos($this->Cset, $needle)===false) ? "N" : "O");
        }
        $SminiC = "OOOO";
        $IminiC = 4;
        $crypt = "";
        while ($code > "") {
            $i = strpos($Cguid, $SminiC);
            if ($i!==false) {
                $Aguid [$i] = "N";
                $Bguid [$i] = "N";
            }
            if (substr($Cguid, 0, $IminiC) == $SminiC) { 
                $crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);
                $made = strpos($Cguid, "N");
                if ($made === false) $made = strlen($Cguid);
                if (fmod($made, 2)==1) $made--;
                for ($i=0; $i < $made; $i += 2) $crypt .= chr(strval(substr($code, $i, 2)));
                    $jeu = "C";
            } else {
                $madeA = strpos($Aguid,"N");
                if ($madeA === false) $madeA = strlen($Aguid);
                $madeB = strpos($Bguid,"N");
                if ($madeB === false) $madeB = strlen($Bguid);
                $made = (($madeA < $madeB) ? $madeB : $madeA );
                $jeu = (($madeA < $madeB) ? "B" : "A" );
                $jeuguid = $jeu . "guid";
                $crypt .= chr(($crypt > "") ? $this->JSwap["$jeu"] : $this->JStart["$jeu"]);
                $crypt .= strtr(substr($code, 0, $made), $this->SetFrom[$jeu], $this->SetTo[$jeu]);
            }
            $code = substr($code, $made);
            $Aguid = substr($Aguid, $made);
            $Bguid = substr($Bguid, $made);
            $Cguid = substr($Cguid, $made);
        }
        $check=ord($crypt[0]);
        for ($i=0; $i<strlen($crypt); $i++) {
            $check += (ord($crypt[$i]) * $i);
        }
        $check %= 103;
        $crypt .= chr($check) . chr(106) . chr(107);
        $i = (strlen($crypt) * 11) - 8;
        $modul = $w/$i;
        for ($i=0; $i<strlen($crypt); $i++) {
            $c = $this->T128[ord($crypt[$i])];
            for ($j=0; $j<count($c); $j++) {
                $this->Rect($x,$y,$c[$j]*$modul,$h,"F");
                $x += ($c[$j++]+$c[$j])*$modul;
            }
        }
    } //fim Code128

    /** 
     * Rotate
     * Rotaciona para impressão paisagem (landscape)
     * @package     FPDF
     * @name        Rotate
     * @version     1.0
     * @author      Oliver
     * @param number $angle 
     * @param number $x
     * @param number $y
     */
    public function Rotate($angle,$x=-1,$y=-1) {
        if($x==-1){
            $x=$this->x;
        }
        if($y==-1){
            $y=$this->y;
       }
       if( isset( $this->angle ) && $this->angle != 0){
            $this->_out('Q');
       }
        $this->angle=$angle;
        if($angle!=0){
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    } //fim função rotate

    /**
     * RoundedRect
     * Desenha um retangulo com cantos arredondados
     * @package     FPDF
     * @name        RoundedRect
     * @version     1.0
     * @author      Maxime Delorme & Christophe Prugnaud
     * @param number $x
     * @param number $y
     * @param number $w
     * @param number $h
     * @param number $r
     * @param string $corners
     * @param string $style 
     */
    public function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F'){
            $op='f';
        } elseif($style=='FD' || $style=='DF') {
            $op='B';
        } else {
            $op='S';
        }    
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false){
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        } else {
            $this->_arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        }    
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false){
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        } else {
            $this->_arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        }    
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false){
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        } else {
            $this->_arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        }    
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false){
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }else{
            $this->_arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        }    
        $this->_out($op);
    }//fim RoundedRect
    
    /**
     * _arc
     * Desenha o arco para arredondar o canto do retangulo
     * @package     FPDF
     * @name        _arc
     * @version     1.0
     * @author      Maxime Delorme & Christophe Prugnaud
     * @param number $x1
     * @param number $y1
     * @param number $x2
     * @param number $y2
     * @param number $x3
     * @param number $y3 
     */
    private function _arc($x1, $y1, $x2, $y2, $x3, $y3){
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    } // fim _Arc
    
    /**
     * DashedRect
     * Desenha um retangulo com linhas tracejadas
     * @package     FPDF
     * @name        DashedRect
     * @version     1.0
     * @author      Antoine Michéa
     * @param number $x1
     * @param number $y1
     * @param number $x2
     * @param number $y2
     * @param number $width
     * @param number $nb 
     */
    public function DashedRect($x1, $y1, $x2, $y2, $width=1, $nb=15) {
        $this->SetLineWidth($width);
        $longueur=abs($x1-$x2);
        $hauteur=abs($y1-$y2);
        if($longueur>$hauteur) {
            $Pointilles=($longueur/$nb)/2;
        }
        else {
            $Pointilles=($hauteur/$nb)/2;
        }
        for($i=$x1;$i<=$x2;$i+=$Pointilles+$Pointilles) {
            for($j=$i;$j<=($i+$Pointilles);$j++) {
                if($j<=($x2-1)) {
                    $this->Line($j,$y1,$j+1,$y1);
                    $this->Line($j,$y2,$j+1,$y2);
                }
            }
        }
        for($i=$y1;$i<=$y2;$i+=$Pointilles+$Pointilles) {
            for($j=$i;$j<=($i+$Pointilles);$j++) {
                if($j<=($y2-1)) {
                    $this->Line($x1,$j,$x1,$j+1);
                    $this->Line($x2,$j,$x2,$j+1);
                }
            }
        }
    }//fim DashedRect

    /**
     * drawTextBox
     * Monta uma caixa de texto
     * @package     FPDF
     * @name        drawTextBox
     * @version     1.0
     * @author      Darren Gates & Adrian Tufa
     * @param string $strText
     * @param number $w
     * @param number $h
     * @param string $align
     * @param string $valign
     * @param boolean $border 
     */
    public function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true) {
        $xi=$this->GetX();
        $yi=$this->GetY();
        $hrow=$this->FontSize;
        $textrows=$this->_drawRows($w,$hrow,$strText,0,$align,0,0,0);
        $maxrows=floor($h/$this->FontSize);
        $rows=min($textrows,$maxrows);
        $dy=0;
        if (strtoupper($valign)=='M'){
            $dy=($h-$rows*$this->FontSize)/2;
        }    
        if (strtoupper($valign)=='B'){
            $dy=$h-$rows*$this->FontSize;
        }    
        $this->SetY($yi+$dy);
        $this->SetX($xi);
        $this->_drawRows($w,$hrow,$strText,0,$align,false,$rows,1);
        if ($border){
            $this->Rect($xi,$yi,$w,$h);
        }    
    }// fim drawTextBox
    
    /**
     * _drawRows
     * Insere linhas de texto na caixa
     * @package     FPDF
     * @name        _drawRows
     * @version     1.0
     * @author      Darren Gates & Adrian Tufa
     * @param number $w
     * @param number $h
     * @param string $txt
     * @param string $border
     * @param string $align
     * @param boolean $fill
     * @param number $maxline
     * @param number $prn
     * @return int 
     */
    private function _drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0){
        $cw=&$this->CurrentFont['cw'];
        if($w==0){
            $w=$this->w-$this->rMargin-$this->x;
        }    
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n"){
            $nb--;
        }    
        $b=0;
        if($border){
            if($border==1){
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            } else{
                $b2='';
                if(is_int(strpos($border,'L'))){
                    $b2.='L';
                }    
                if(is_int(strpos($border,'R'))){
                    $b2.='R';
                }    
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb){
            $c=$s[$i];
            if($c=="\n"){
                if($this->ws>0){
                    $this->ws=0;
                    if ($prn==1){
                        $this->_out('0 Tw');
                    }
                }
                if ($prn==1) {
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                }
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2){
                    $b=$b2;
                }    
                if ( $maxline && $nl > $maxline ){
                    return substr($s,$i);
                }    
                continue;
            }
            if($c==' '){
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax){
                if($sep==-1){
                    if($i==$j){
                        $i++;
                    }    
                    if($this->ws>0){
                        $this->ws=0;
                        if ($prn==1){
                            $this->_out('0 Tw');
                        }
                    }
                    if ($prn==1) {
                        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                    }
                } else {
                    if($align=='J') {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        if ($prn==1){
                            $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                        }    
                    }
                    if ($prn==1){
                        $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    }
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2){
                    $b=$b2;
                }    
                if ( $maxline && $nl > $maxline ){
                    return substr($s,$i);
                }    
            } else {
                $i++;
            }    
        }
        if($this->ws>0) {
            $this->ws=0;
            if ($prn==1){
                $this->_out('0 Tw');
            }
        }
        if($border && is_int(strpos($border,'B'))){
            $b.='B';
        }    
        if ($prn==1) {
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
        }
        $this->x=$this->lMargin;
        return $nl;
    }//fim _drawRows
    
    /**
     * WordWrap
     * Quebra o texto para caber na caixa
     * @package     FPDF
     * @name        WordWrap
     * @version     1.0
     * @author      Ron Korving
     * @param type $text
     * @param type $maxwidth
     * @return int 
     */
    public function WordWrap(&$text, $maxwidth){
        $text = trim($text);
        if ($text===''){
            return 0;
        }    
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;
        foreach ($lines as $line) {
            $words = preg_split('/ +/', $line);
            $width = 0;
            foreach ($words as $word) {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth){
                    // Word is too long, we cut it
                    for($i=0; $i<strlen($word); $i++){
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if($width + $wordwidth <= $maxwidth){
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        } else {
                            $width = $wordwidth;
                            $text = rtrim($text)."\n".substr($word, $i, 1);
                            $count++;
                        }
                    }
                } elseif($width + $wordwidth <= $maxwidth) {
                    $width += $wordwidth + $space;
                    $text .= $word.' ';
                } else {
                    $width = $wordwidth + $space;
                    $text = rtrim($text)."\n".$word.' ';
                    $count++;
                }
            }
            $text = rtrim($text)."\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    } //fim WordWrap
    
    /**
     * CellFit
     * Celula com escala horizontal caso o texto seja muito largo
     * @package     FPDF
     * @name        CellFit
     * @version     1.0
     * @author      Patrick Benny
     * @param number $w
     * @param number $h
     * @param string $txt
     * @param number $border
     * @param number $ln
     * @param string $align
     * @param boolean $fill
     * @param string $link
     * @param boolean $scale
     * @param boolean $force 
     */
    public function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true){
        $str_width=$this->GetStringWidth($txt);
        if($w==0){
            $w = $this->w-$this->rMargin-$this->x;
        }    
        $ratio = ($w-$this->cMargin*2)/$str_width;
        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit){
            if ($scale){
                //Calcula a escala horizontal
                $horiz_scale=$ratio*100.0;
                //Ajusta a escala horizontal
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            } else {
                //Calcula o espaçamento de caracteres em pontos
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->_MBGetStringLength($txt)-1,1)*$this->k;
                //Ajusta o espaçamento de caracteres
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Sobrescreve o alinhamento informado (desde que o texto caiba na celula)
            $align='';
        }
        //Passa para o método cell
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
        //Reseta o espaçamento de caracteres e a escala horizontal
        if ($fit){
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
        }    
    }//fim CellFit

    /**
     * CellFitScale
     * Celula com escalamento horizontal somente se necessário
     * @package     FPDF
     * @name        CellFitScale
     * @version     1.0
     * @author      Patrick Benny
     * @param number $w
     * @param number $h
     * @param string $txt
     * @param number $border
     * @param number $ln
     * @param string $align
     * @param boolean $fill
     * @param string $link 
     */
    public function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    /**
     * CellFitScaleForce
     * Celula com escalamento forçado
     * @package     FPDF
     * @name        CellFitScaleForce
     * @version     1.0
     * @author      Patrick Benny
     * @param number $w
     * @param number $h
     * @param string $txt
     * @param number $border
     * @param number $ln
     * @param string $align
     * @param boolean $fill
     * @param string $link 
     */
    public function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    /**
     * CellFitSpace
     * Celula com espaçamento de caracteres somente se necessário
     * @package     FPDF
     * @name        CellFitSpace
     * @version     1.0
     * @author      Patrick Benny
     * @param number $w
     * @param number $h
     * @param string $txt
     * @param number $border
     * @param number $ln
     * @param string $align
     * @param boolean $fill
     * @param string $link 
     */
    public function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
    
    /**
     * CellFitSpaceForce
     * Celula com espaçamento de caracteres forçado
     * @package     FPDF
     * @name        CellFitSpaceForce
     * @version     1.0
     * @author      Patrick Benny
     * @param number $w
     * @param number $h
     * @param string $txt
     * @param number $border
     * @param number $ln
     * @param string $align
     * @param boolean $fill
     * @param string $link 
     */
    public function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }
    
    /**
     * _MBGetStringLength
     * Patch para trabalhar com textos de duplo byte CJK
     * @package     FPDF
     * @name        _MBGetStringLength
     * @version     1.0
     * @author      Patrick Benny
     * @param string $s
     * @return int 
     */
    private function _MBGetStringLength($s){
        if($this->CurrentFont['type']=='Type0'){
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++){
                if (ord($s[$i])<128){
                    $len++;
                } else {
                    $len++;
                    $i++;
                }
            }
            return $len;
        } else {
            return strlen($s);
        }    
    }


    /**
     * DashedLine
     * Desenha uma linha horizontal tracejada com o FPDF
     * @package NFePHP
     * @name DashedHLine
     * @version 1.0.1
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param number $x Posição horizontal inicial, em mm
     * @param number $y Posição vertical inicial, em mm
     * @param number $w Comprimento da linha, em mm
     * @param number $h Espessura da linha, em mm
     * @param number $n Numero de traços na seção da linha com o comprimento $w
     * @return none
     */
    public function DashedHLine($x,$y,$w,$h,$n) {
        $this->SetDrawColor(110);
        $this->SetLineWidth($h);
        $wDash=($w/$n)/2; // comprimento dos traços
        for( $i=$x; $i<=$x+$w; $i += $wDash+$wDash ) {
            for( $j=$i; $j<= ($i+$wDash); $j++ ) {
                if( $j <= ($x+$w-1) ) {
                    $this->Line($j,$y,$j+1,$y);
                }
            }
        }
        $this->SetDrawColor(0);
    } //fim função DashedHLine

   /**
    * DashedVLine
    * Desenha uma linha vertical tracejada com o FPDF
    * @package NFePHP
    * @name DashedVLine
    * @version 1.0
    * @author Roberto L. Machado <linux.rlm at gmail dot com>
    * @author Guilherme Calabria Filho <guiga86 at gmail dot com>
    * @param number $x Posição horizontal inicial, em mm
    * @param number $y Posição vertical inicial, em mm
    * @param number $w Comprimento da linha, em mm
    * @param number $yfinal Espessura da linha, em mm
    * @param number $n Numero de traços na seção da linha com o comprimento $w
    * @return none
    */
    public function DashedVLine($x,$y,$w,$yfinal,$n) {
        $this->SetLineWidth($w);
        //Organizando valores
        if($y>$yfinal) {
            $aux = $yfinal;
            $yfinal = $y;
            $y = $aux;
        }
        while($y<$yfinal&&$n>0){
            $this->Line($x,$y,$x,$y+1);
            $y += 3;
            $n--;
        }
    } //fim função DashedVLine
}

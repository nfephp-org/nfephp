<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// nao permitir qualquer aviso no codigo pdf
error_reporting(E_ALL);
set_time_limit(1800);
define('FPDF_FONTPATH','./libs/fpdf/font/');
define('IMGPATH','./images/');
require_once('./libs/fpdf/pdfbarcode128.inc');
require_once('./libs/fpdf/fpdf.php');
require_once('baseconfig_inc.php');

class DANFE extends FPDF {

        var $num; //numero da NF

        function SetNum($numero){
            //Set numero da NF
            $this->num=$numero;
        }

        function Cabecalho() {

        }

        function Footer() {
            //Vai para 15 mm da borda inferior
            $this->SetY(-15);
            $this->SetX(5);
            //Seleciona Arial itálico 8
            $this->SetFont('Arial','I',6);
            //Imprime o número da página centralizado
            $txtSLC = 'SLC Balanças Comércio e Manutenção Ltda. ' . chr(10);
            $txtEnd = 'Rua Porto Alegre, 215 - Ch. Santa Marta - Santana de Parnaiba - SP CEP 06529-195 ' . chr(10);
            $txtTel = 'Fone : (11) 4705-3676 contato@slcbalancas.com.br';
            $page = 'Pagina '.$this->PageNo().' de '.'{nb}';
            $this->Line(5,280,205,280);
            $this->Line(5,280.5,205,280.5);
            $this->SetFont('Arial','B',8);
            $this->Cell(150,3,$txtSLC,'0','0','L',false,'http://www.slcbalancas.com.br');
            $this->SetFont('Arial','B',8);
            $this->Cell(50,3, $page, '0', '0', 'R');
            $this->Ln();
            $this->SetFont('Arial','I',6);
            $this->Cell(150,3,$txtEnd,'0','0','L');
            $this->SetFont('Arial','I',6);
            $this->Cell(47,3,'Documento gerado automaticamente em '.date('d/m/Y H:i:s'),0,0,'R');
            $this->Ln();
            $this->Cell(150,3,$txtTel,'0','0','L');
            $this->SetFont('Arial','B',8);
            $this->Cell(47,3,'Relatório de Calibração : '.$this->num,'0','0','R');

        }

        function caixaTexto($x,$y,$rotulo,$texto,$l,$h,$tamfont,$alinha){
            //$rotulo
            // $texto
            //$l
            //$h
            //$tamfont
            //$alinha
            
            //$x= $this->GetX();
            //$y = $this->GetY();
            $this->SetDrawColor(100,100,100);
            $this->SetXY($x, $y-2);
            $this->SetFont('Arial', 'I', 6);
            $this->Cell($l,$h, $rotulo, 0, 0, 'L');
            $this->SetXY($x, $y);
            $this->SetFont('Arial', 'B', $tamfont);
            $this->Cell($l,$h,$texto,1,0,$alinha);
        }

        // Table
        function fTable($header,$data,$w,$alinh='') {
            //Colors, line width and bold font
            $this->SetFillColor(200,200,200);
            $this->SetTextColor(0,0,0);
            $this->SetDrawColor(100,100,100);
            //$this->SetLineWidth(.3);
            $x = $this->GetX();
            $this->SetFont('Arial','B',8);
            //Header
            //$w=array(20,20,20,20,20);
            for($i=0;$i<count($header);$i++) {
                $this->Cell($w[$i],4,$header[$i],1,0,'C',true);
            }
            $this->Ln();
            //Color and font restoration
            $this->SetFillColor(255,255,255);
            $this->SetFont('Arial','',8);
            //Data
            $fill=false;
            $lin = 0;
            foreach($data as $row) {
              $this->SetX($x);
              for ($n=0;$n<count($w);$n++){
                $alinhamento = 'R';
                if($alinh<>''){
                      $alinhamento = $alinh[$n];
                }
                $this->Cell($w[$n],4,$row[$n],1,0,$alinhamento,$fill);
              }
              //$this->Cell($w[1],3,$row[1],1,0,'R',$fill);
              //$this->Cell($w[2],3,number_format($row[2]),1,0,'R',$fill);
              //$this->Cell($w[3],3,number_format($row[3]),1,0,'R',$fill);
              //$this->Cell($w[4],3,number_format($row[3]),1,0,'R',$fill);
              $this->Ln();

              $fill=!$fill;
           }
           //$this->Cell(array_sum($w),0,'','T');
        }

}

//dados
    $numero = '1234-08';

    $data = '12/12/2008';

    $cliente = 'PPG Industrial do Brasil Ltda';

    $end = 'Av. Jordano Mendes, 1300 Bairro xxxxx - Cajamar - SP CEP: 2020202';

    $equip = 'Balança digital ALFA';
    // fim dos dados
    

// setup do relatorio
    $orientacao = 'P'; //portrait

    // margens
    $margSup = 5;
    $margEsq = 5;
    $margDir = 5;

    // posição inicial do relatorio
    $y = 2;
    $x = 5;

    
    // Geração do relatorio
    // instancia a classe
    $pdf= new DANFE();
    //                         000000000011111111112222222222333333333344444444445555555555
    //                         012345678901234567890123456789012345678901234567890123456789
    $code = new pdfbarcode128('35080599999090910270550010000000015180051273', 0.8 );

    // estabelece contagem de paginas
    $pdf->AliasNbPages();
    // fixa as margens
    $pdf->SetMargins($margEsq,$margSup,$margDir);
    $pdf->SetDrawColor(100,100,100);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetAuthor($empresa);
    $pdf->SetNum($numero);

    // inicia o documento
    $pdf->Open();

    // adiciona a primeira página
    $pdf->AddPage($orientacao);

    // coloca o logo
    $pdf->Image(IMGPATH.'logo_novo_azul.jpg',$x,$y,30,'','jpeg');

    $code->set_pdf_document($pdf);
	$width = $code->get_width();
    //$code->draw_barcode($x, $y, $bar_height, $print_text = false);
	$code->draw_barcode(130, 3, 22, false );

    //$code->draw_barcode(130, 3, 22, false );

    // estabelece a fonte
    $pdf->SetFont('Arial', 'B', 16);

    // estabelece a cor da fonte
    $pdf->SetTextColor(0,0,0);
    
    $y += 6;

    //posiciona o cursor
    $pdf->SetXY(60,$y);

    // escreve o texto na posição determinada
    $pdf->Write(3, iconv('UTF-8','ISO-8859-1','DANFE'));

    // estabelece a fonte
    $pdf->SetFont('Arial', 'B', 10);
    // estabelece a cor da fonte
    $pdf->SetTextColor(0,0,0);

    $y += 6;
    // move o cursor para a posição XY
    $pdf->SetXY(14,$y);
    // caixa numero
    $pdf->caixaTexto(14,$y,iconv('UTF-8','ISO-8859-1','Número'), $numero,35,8,16,'R');

    $relY = 10;
    // move o cursor para a posição XY

    $pdf->SetXY(35,$y+$relY);

    // caixa Cliente
    //$pdf->caixaTexto('x', $width,80,4,10,'L');

    // move o cursor para a posição XY
    //$pdf->SetXY(145,$y+$relY);
    // caixa Data
    //$pdf->caixaTexto('Data da Calibração ', $data, 35, 4, 10, 'C');
    //$relY += 5;
    // move o cursor para a posição XY
    //$pdf->SetXY(35,$y+$relY);
    // caixa Endereço
    //$pdf->caixaTexto('Endereço ', $end, 145, 4, 10, 'L');
    //$relY += 5;
    // move o cursor para a posição XY
    //$pdf->SetXY(35,$y+$relY);
    // caixa Equimpamento
    //$pdf->caixaTexto('Equipamento ', $equip, 66, 4, 10, 'L');
    // move o cursor para a posição XY


    // envia ao browser para abrir com o plugin
    //$pdf->Output();

    // envia ao browser para salvar
    //$pdf-Output($numero.'.pdf','D')
    echo $width;
?>

<?php
/*!
Class: PdfBarcode128
Version : 1.0
Released: 05-30-2002
Author: GuinuX <guinux@cosmoplazza.com>


**** usage:
See the example scripts.

License: The GNU General Public License (GPL)
http://www.opensource.org/licenses/gpl-license.html

For any suggestions or bug report please contact me : guinux@cosmoplazza.com

!*/

	//define('FPDF_FONTPATH','font/');
	require_once('barcode128.inc');
	require_once('fpdf.php');

	class pdfbarcode128 extends barcode128 {
		var $_pdf;
		var $_char_width;
		var $_bar_width;
		var $_data;
		
		function pdfbarcode128( $data, $char_width ) {
			$this->_bar_width = round($char_width/11, 1);
			$this->_char_width = $this->_bar_width * 11;
			$this->_data = $data;
			$this->barcode128($data);
		}
		
		function get_width() {
			return barcode128::get_width($this->_char_width);
		}
	
		function set_pdf_document( &$pdf ) {
			$this->_pdf = &$pdf;
		}
		
		function _draw_line( $x, $y, $nb, $height, $bar ) {
			if ($bar) {
				$this->_pdf->SetDrawColor(0, 0, 0);
				$this->_pdf->SetFillColor(0, 0, 0);
			} else {
				$this->_pdf->SetDrawColor( 255, 255, 255);
				$this->_pdf->SetFillColor( 255, 255, 255);
			}
			$this->_pdf->SetLineWidth(0.000);
			$this->_pdf->Rect( $x, $y, $nb*$this->_bar_width, $height, 'FD');
		}
	
		function draw_barcode( $x, $y, $bar_height, $print_text = false ) {
			$start_x = $x;
			$start_y = $y;
			
			$this->_compute_pattern();
			
			// Quiet Zone
			$this->_pdf->SetLineWidth(0.0000);
			$this->_pdf->SetDrawColor(255, 255, 255);
			$this->_pdf->SetFillColor(255, 255, 255);
			$this->_pdf->Rect($x, $y, $this->_bar_width*10, $bar_height, 'FD');
			$x += $this->_bar_width*10;

			foreach($this->_pattern as $pos=>$digit) {
				$digit = explode( ' ', $digit);
				$bar = true;
				foreach ($digit as $tmp=>$nb) {
					$this->_draw_line( $x, $y, $nb, $bar_height, $bar );
					$x += ($this->_bar_width*$nb)+0.05;
					$bar = ($bar==true?false:true);
				}
			}

			// Quiet Zone
			$this->_pdf->SetLineWidth(0.0000);
			$this->_pdf->SetDrawColor(255, 255, 255);
			$this->_pdf->SetFillColor(255, 255, 255);
			$this->_pdf->Rect($x, $y, $this->_bar_width*10, $bar_height, 'FD');
			$x += $this->_bar_width*10;

			if ($print_text) {
				$font_size = floor($this->get_width() / strlen($this->_data)*1.5);
				$this->_pdf->SetFont('arial', '', $font_size);
				$this->_pdf->SetXY( $start_x, $start_y + $bar_height );
				$this->_pdf->Cell( $x - $start_x, $font_size/2, $this->_data, 0, 0, 'C' );
			}
		}
	} // End of Class
?>
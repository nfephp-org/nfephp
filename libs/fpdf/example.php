<?php

require_once('pdfbarcode128.inc');

	$pdf = new FPDF();
	$code = new pdfbarcode128('FR5975', 6 );
	//$pdf->Open();
	$pdf->AddPage();
	$code->set_pdf_document($pdf);
	$width = $code->get_width();
	$code->draw_barcode(10, 2, 15, true );
	$pdf->Output();

    unset($code);
	unset($pdf);

?>
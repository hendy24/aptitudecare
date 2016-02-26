<?php
// 	SetX ($x, $rtloff=false)
// 	SetY ($y, $resetx=true, $rtloff=false)

class labelBook extends label{

	/**
	 * Template d'impression étiquette
	 */
	function template($x, $y, $dataPrint){

	// define barcode style
$styleB = array(
	'position' => '',
	'align' => 'C',
	'stretch' => false,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => false,
	'hpadding' => 'auto',
	'vpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 10,
	'stretchtext' => 3 // int $style['stretchtext']: 0 = disabled; 1 = horizontal scaling only if necessary; 2 = forced horizontal scaling; 3 = character spacing only if necessary; 4 = forced character spacing. 
);

	$marginoffset = 2;
	$x += $this->labelMargin+$marginoffset;
	$y += $this->labelMargin+$marginoffset;
	
		// Etiquette
		$aff_border = 0;
		$ref_font = max($this->labelWidth, $this->labelHeight);
		$des_font = 0.5* min($this->labelWidth, $this->labelHeight);

		$this->setY($y, false);
		$this->setX($x);
		
// Cell ($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

		$this->SetFont("helvetica", "B", $des_font);
		$this->setX($x);
		$this->Cell(45 , 0,"AU TC Library",$aff_border,1,'C',0);

// write1DBarcode ($code, $type, $x='', $y='', $w='', $h='', $xres='', $style='', $align='')

	$this->SetFont('helvetica', '', 12);

	$this->write1DBarcode($dataPrint, 'C128', $x, $y+5, 45, 18, 0.4, $styleB, 'N');
//	$this->write1DBarcode("Hello", 'C128', $x, $y, 45, 14, 0.4, $styleB, 'N');

	}

}//End of class labels

?>
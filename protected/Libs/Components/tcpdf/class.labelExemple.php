<?php

class labelExemple extends label{

	/**
	 * Template d'impression étiquette
	 */
	function template($x, $y, $dataPrint){

	$x += $this->labelMargin;
	$y += $this->labelMargin;
	 
		// Etiquette
		$aff_border = 0;
		$ref_font = max($this->labelWidth, $this->labelHeight);
		$des_font = 0.5* min($this->labelWidth, $this->labelHeight);

		$this->setX($x);
		$this->setY($y, false);

		$this->SetFont("helvetica", "BI", $des_font);
		$this->setX($x);
		$this->writeHTMLCell(0, 0, '', '', $dataPrint, 0, 1, 0, true, '', true);

	}

}//End of class labels

?>
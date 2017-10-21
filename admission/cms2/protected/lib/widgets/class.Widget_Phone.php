<?php

class Widget_Phone extends Widget_Text {

	public function jQueryReady() {
		echo <<<END
$("#{$this->getID()}").mask("(999)-999-9999");
END;
	}
}
<?php

class Widget_Colorpicker extends Widget {

	public function jqueryReady() {
		ob_start();
		echo <<<END
		$("#{$this->getID()}_colorpicker").farbtastic('#{$this->getID()}');
END;
		return ob_get_clean();
	}

	public function jquery() {

	}

	public function render() {
		$id = $this->getID();
		$name = $this->getName();
		$str = "";
		$idfield = $this->model->getPrimaryKeyField();
		if ($this->value == '') $this->value = "#ffffff";
		$str = "<div id=\"{$id}_colorpicker\"></div>";
		$str .= "<input type=\"text\" size=\"7\" name=\"{$name}\" id=\"{$id}\" value=\"{$this->value}\" />";

		return $str;

	}

	public function save() {
		
	}

}
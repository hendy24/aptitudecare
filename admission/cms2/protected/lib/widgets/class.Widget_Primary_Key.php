<?php

class Widget_Primary_Key extends Widget {

	public function render() {

		$str = "<input type=\"hidden\" name=\"{$this->getName()}\" id=\"{$this->getID()}\"";
		if ($this->value != '') {
			$str .= " value=\"" . $this->value . "\"";
		}
		$str .= " />";
		return $str;

	}

}

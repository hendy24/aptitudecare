<?php

class Widget_Currency extends Widget {

	private $size = 10;

	public function jQueryReady() {
	}

	public function render() {

		$str = "<input type=\"text\" name=\"{$this->getName()}\" id=\"{$this->getID()}\" size=\"{$this->size}\"";
		if ($this->class != '') {
			$str .= " class=\"{$this->css_class}\"";
		}
		if ($this->value != '') {
			$str .= " value=\"" . smarty_money($this->value) . "\"";
		}
		$str .= " />";
		return $str;

	}

}
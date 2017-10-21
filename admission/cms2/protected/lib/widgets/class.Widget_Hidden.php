<?php

class Widget_Hidden extends Widget {
	public function render() {

		$str = "<input type=\"hidden\" name=\"{$this->getName()}\" id=\"{$this->getID()}\"";
		if ($this->value != '') {
			$str .= " value=\"" . htmlspecialchars($this->value) . "\"";
		}
		$str .= " />";
		return $str;

	}
	
}
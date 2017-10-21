<?php

class Widget_Country extends Widget {

	public function render() {

		$countries = getCountries();
		$str = "<select name=\"{$this->getName()}\" id=\"{$this->getID()}\">\n";
		foreach ($countries as $abbr => $cName) {
			if ($abbr == $this->value) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			$str .= "<option value=\"{$abbr}\"{$selected}>{$cName}</option>\n";
		}
		$str .= "</select>";
		return $str;
	}

}
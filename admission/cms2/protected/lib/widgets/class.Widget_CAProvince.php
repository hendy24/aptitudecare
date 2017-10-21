<?php

class Widget_CAProvince extends Widget {

	public function render() {

		$provinces = getCAProvinces();
		$str = "<select name=\"{$this->getName()}\" id=\"{$this->getID()}\">\n";
		foreach ($provinces as $abbr => $pName) {
			if ($abbr == $this->value) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			$str .= "<option value=\"{$abbr}\"{$selected}>{$pName}</option>\n";
		}
		$str .= "</select>";
		return $str;
	}

}
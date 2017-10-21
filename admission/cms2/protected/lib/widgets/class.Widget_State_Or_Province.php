<?php

class Widget_State_Or_Province extends Widget {

	public function render() {

		$provinces = getCAProvinces();
		$states = getUSAStates();
		$places = array_merge($provinces, $states);
		ksort($places);
		$str = "<select name=\"{$this->getName()}\" id=\"{$this->getID()}\">\n";
		foreach ($places as $abbr => $pName) {
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
<?php

class Widget_State extends Widget {

	public function render() {

		$states = getUSAStates();
		$str = "<select name=\"{$this->getName()}\" id=\"{$this->getID()}\">\n";
		foreach ($states as $abbr => $stateName) {
			if ($abbr == $this->value) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			$str .= "<option value=\"{$abbr}\"{$selected}>{$stateName}</option>\n";
		}
		$str .= "</select>";
		return $str;
	}

}
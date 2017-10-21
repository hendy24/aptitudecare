<?php

class Widget_Password extends Widget {

	public function render() {
		$size = $this->options["size"];
		if ($size == '') {
			$size = "50";
		}
		if ($this->options["readOnly"] == true) {
			if ($this->options["type"] != "encrypted") {
				$str = $this->value;
			}
		} else {
			if ($this->options["type"] == "encrypted") {
				$str = "<input type=\"password\" name=\"{$this->getName()}\" id=\"{$this->getID()}\" size=\"{$size}\"";
			} else {
				if ($this->options["type"] == "clear-masked") {
					$str = "<input type=\"password\" name=\"{$this->getName()}\" id=\"{$this->getID()}\" size=\"{$size}\"";
				} elseif ($this->options["type"] == "clear-unmasked") {
					$str = "<input type=\"text\" name=\"{$this->getName()}\" id=\"{$this->getID()}\" size=\"{$size}\"";
				}
			}
			if ($this->value != '') {
				$str .= " value=\"" . $this->value . "\"";
			}
			if ($this->class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			$str .= " />";
		}
		return $str;

	}

}
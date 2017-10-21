<?php

class Widget_Text extends Widget {

	public function render() {
		$size = $this->options["size"];
		if ($size == '') {
			$size = "50";
		}
		if ($this->options["readOnly"] == true) {
			$str = $this->value;
		} else {
			$str = "<input type=\"text\" name=\"{$this->getName()}\" id=\"{$this->getID()}\" size=\"{$size}\"";
			if ($this->class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			if ($this->value != '') {
				$str .= " value=\"" . htmlspecialchars($this->value) . "\"";
			}
			$str .= " />";
		}
		return $str;

	}

}
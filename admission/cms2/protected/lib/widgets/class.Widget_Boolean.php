<?php

class Widget_Boolean extends Widget {

	public function render() {

		if ($this->options["readOnly"] == true) {
			if ($this->value == 1) {
				echo "<img src=\"{$ENGINE_URL}/images/icons/checkmark.png\" />";
			}
		} else {
			$str = "<input type=\"checkbox\" name=\"{$this->getName()}\" value=\"1\" id=\"{$this->getID()}\"";
			if ($this->class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			if ($this->value == 1) {
				$str .= " checked";
			}
			$str .= " />";
		}
		return $str;

	}

}
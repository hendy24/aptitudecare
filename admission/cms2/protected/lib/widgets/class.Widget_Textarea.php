<?php

class Widget_Textarea extends Widget {

	public function render() {
		if ($this->options["readOnly"] == true) {
			$str = $this->value;
		} else {
			$str = "<textarea id=\"{$this->getID()}\" name=\"{$this->getName()}\" rows=\"20\" cols=\"60\" style=\"width: 400px;\"";
			if ($this->class != '') {
				$str .= " class=\"{$this->css_class}";
			}
			$str .= ">";
			if ($this->value != '') {
				$str .= $this->value;
			}
			$str .= "</textarea>";
		}
		return $str;

	}

}
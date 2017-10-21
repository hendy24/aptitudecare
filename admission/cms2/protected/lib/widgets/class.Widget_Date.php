<?php

class Widget_Date extends Widget {

	public function jqueryReady() {
		ob_start();
		echo <<<END
		$("#{$this->getID()}").datepicker({
	showOn: 'button',
	buttonText: 'Select'
});
END;
		return ob_get_clean();
	}

	public function jquery() {

	}

	public function render() {
		if ($this->options["readOnly"] == true) {
			$str = $this->value;
		} else {
			$id = $this->getID();
			$name = $this->getName();
			if ($this->value != '' && $this->value != '0000-00-00')
				$value = date("m/d/Y", strtotime($this->value));
			$str = "<input id=\"{$this->getID()}\" name=\"record[{$this->fieldname}]\" type=\"text\" value=\"{$value}\" size=\"11\"";
			if ($this->css_class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			$str .= " />\n";
		}
		return $str;


	}

}
<?php

class Widget_Datetime extends Widget {
	
	public function jqueryReady() {
		ob_start();
		echo <<<END

		$("#datetime-clear-{$this->getID()}").click(function(e) {
			$("#{$this->getID()}").val("").change();
		});
			
		$("#{$this->getID()}").datetimepicker( { dateFormat: "yy-mm-dd", timeFormat: "hh:mm:ss" } );
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

			$str = "<input id=\"{$this->getID()}\" name=\"record[{$this->fieldname}]\" type=\"text\" value=\"{$this->value}\" size=\"20\"";
			if ($this->css_class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			$str .= " /> <input type='button' id='datetime-clear-{$this->getID()}' value='Clear' />\n";
		}
		return $str;

	}

}
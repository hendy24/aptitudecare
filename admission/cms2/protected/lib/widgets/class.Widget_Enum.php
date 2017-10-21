<?php

class Widget_Enum extends Widget {

	public function render() {
		if ($this->options["readOnly"] == true) {
			$str = $this->value;
		} else {
			$options = db()->enumoptions($this->model->getTable(), $this->fieldname);
			$is_nullable = db()->column_is_nullable($this->model->getTable(), $this->fieldname);

			$str = "<select name=\"{$this->getName()}\" id=\"{$this->getID()}\"";
			if ($this->class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			$str .= ">";
			if ($is_nullable == true) {
				$str .= "<option value=''></option>";
			}
			if (is_array($options)) {
				foreach ($options as $o) {
					$str .= "<option value=\"" . htmlspecialchars($o) . "\"";
					if ($o == $this->value) {
						$str .= " selected";
					}
					$str .= ">{$o}</option>\n";
				}
			}
			$str .= "</select>";
		}
		return $str;

	}

}
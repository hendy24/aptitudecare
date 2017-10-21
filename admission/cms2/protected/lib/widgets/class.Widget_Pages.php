<?php

class Widget_Pages extends Widget {

	public function jQueryReady() {
	}

	public function render() {
		if ($this->options["readOnly"] == true) {
			$str = $this->value;
		} else {
			$options = PageController::getPages();
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
					$str .= "<option rel=\"{$o->id}\" value=\"{$o}\"";
					if ($o == $this->value || preg_match("/^{$this->value}\/.*$/", $o)) {
						$str .= " selected";
					}
					$str .= ">{$o}</option>\n";
				}
			}
			$str .= "</select>";
			$str .= "&nbsp;&nbsp;&nbsp; <a href=\"{$SITE_URL}/?page=admin&amp;action=form&amp;m=CMS_Dynpage\" id=\"editlink_{$this->getID()}\"><span class=\"related-link-label\" id=\"editlabel_{$this->getID()}\">Add new page</span></a>";
		}
		return $str;

	}

}
<?php

class Widget_Related_Single extends Widget {

	// can only call these two methods AFTER initialization.
	public function getForeignModel() {
		if ($this->options["table"] != '') {
			return Model::clsname($this->options["table"]);
		} else {
			return Model::clsname($this->fieldname);
		}
	}
	
	public function getForeignModelTitle() {
		$foreignModel = $this->getForeignModel();
		return $foreignModel::getModelTitle();
	}

	public function jQueryReady() {
		$m = $this->getForeignModel();
		$obj = new $m;
		$SITE_URL = SITE_URL;

		ob_start();
		echo <<<END

$("#{$this->getID()}").change(function() {
	var opt = $("#{$this->getID()} option:selected").text();
	var html ;
	if (opt == '') {
		html = 'Add {$m::$modelTitle}';
		href = '{$SITE_URL}/?page=admin&action=form&m={$m}';
	} else {
		html = 'Edit ' + opt;
		href = '{$SITE_URL}/?page=admin&action=form&m={$m}&{$obj->getPrimaryKeyField()}=' + $(this).val();
	}
	$("#related_single_editlabel_{$this->getID()}").html(html);
	$("#related_single_editlink_{$this->getID()}").attr("href", href);

}).trigger("change");
END;
		return ob_get_clean();
	}

	public function render() {

		if ($this->options["readOnly"] == true) {
			//$option = $this->model->related($this->fieldname, true);
			if ($this->options["table"] != '') {
				$m = Model::clsname($this->options["table"]);
			} else {
				$m = Model::clsname($this->fieldname);
			}
			$option = new $m;
			$option->load($this->model->{$this->fieldname});
			$str = $option->getTitle();
		} else {
			$is_nullable = db()->column_is_nullable($this->model->getTable(), $this->fieldname);


			$str = "<select name=\"{$this->getName()}\" id=\"{$this->getID()}\"";
			if ($this->css_class != '') {
				$str .= " class=\"{$this->css_class}\"";
			}
			$str .= ">\n";
			if ($is_nullable == true || $this->options["forceFirstEmpty"] == true) {
				$str .= "<option value=''></option>";
			}
			if ($this->options["table"] != '') {
				$m = Model::clsname($this->options["table"]);
			} else {
				$m = Model::clsname($this->fieldname);
			}
			$obj = new $m;
			$options = $obj->fetchForAdmin();
			if (is_array($options)) {
				foreach ($options as $o) {
					$str .= "<option value=\"{$o->id}\"";
					if ($o->id == $this->value) {
						$str .= " selected";
					}
					$str .= ">{$o->getTitle()}</option>\n";
				}
			}
			$str .= "</select>";
			$str .= "&nbsp;&nbsp;&nbsp; <a href=\"#\"' id=\"related_single_editlink_{$this->getID()}\"><span class=\"related-link-label\" id=\"related_single_editlabel_{$this->getID()}\"></span></a>";
		}
		return $str;

	}

}

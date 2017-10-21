<?php

class Widget_Related_Multi extends Widget {
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
	
	public function render() {
		$str = "\n";
		$options = $this->model->related($this->fieldname, false);
		$options_mine = $this->model->related($this->fieldname, true);
		if (! is_array($options_mine) ) {
			$options_mine = array($options_mine);
		}
		$ids_mine = array();
		foreach ($options_mine as $o) {
			$ids_mine[] = $o->pk();
		}
		if ($this->options["readOnly"] == true) {
			$str .= "<ul>\n";
			foreach ($options as $o) {
				$str .= "<li>&radic; {$o->getTitle()}</li>\n";
			}
			$str .= "</ul>\n";
		} else {
			foreach ($options as $o) {
				if (in_array($o->pk(), $ids_mine)) {
					$str .= "<input type=\"checkbox\" name=\"{$this->getName()}[]\" value=\"{$o->pk()}\" checked /> {$o->getTitle()} 
";
				} else {
					$str .= "<input type=\"checkbox\" name=\"{$this->getName()}[]\" value=\"{$o->pk()}\" /> {$o->getTitle()} 
";
				}
				$str .= "&nbsp;&nbsp;&nbsp;<a href=\"" . SITE_URL . "/?page=admin&amp;action=form&amp;m=" . get_class($o) . "&amp;{$o->getPrimarykeyField()}={$o->pk()}\">Edit this &raquo;</a><br />";
			}
		}
		return $str;

	}

}

<?php
class Widget_Admin_Models extends Widget {

	public function render() {
		$str = "\n";
		$options = CMS_Table::getCMSTables();

		// filter out the tables whose models don't use the admin
		array_filter($options, function($o) {
			$cls = Model::clsname($o);
			if ($cls::$inAdmin == false) {
				return false;
			}
			return true;
		});


		//$options_mine = $this->model->related($this->fieldname, true);

		//$ids_mine = array();
		//foreach ($options_mine as $o) {
		//	$ids_mine[] = $o->pk();
		//}
		$this->value = unserialize($this->value);
		if ($this->options["readOnly"] == true) {
		} else {
			foreach ($options as $o) {
				$cls = Model::clsname($o);
				if (in_array($cls, $this->value)) {
					$str .= "<input type=\"checkbox\" name=\"{$this->getName()}[]\" value=\"{$cls}\" checked /> {$cls::getModelTitle()} <br />
";
				} else {
					$str .= "<input type=\"checkbox\" name=\"{$this->getName()}[]\" value=\"{$cls}\" /> {$cls::getModelTitle()} <br />
";
				}
			}
		}
		return $str;
	}

}

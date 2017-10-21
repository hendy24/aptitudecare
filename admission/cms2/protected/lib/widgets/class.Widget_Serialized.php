<?php

class Widget_Serialized extends Widget {

	public function render() {
		$str = '';
		$obj = unserialize($this->value);
		if (is_object($obj) || is_array($obj)) {
			foreach ($obj as $key => $val) {
				$str .= "<strong>{$key}:</strong>&nbsp;&nbsp;";
				if (is_object($val) || is_array($val)) {
					$str .= implode(",", array_values($val));
				} else {
					$str .= $val;
				}
				$str .= "<br />";
			}
		} else {
			$str .= $this->value;
		}
		return $str;
	}

}
<?php

class Widget_File extends Widget {

	public function getAssetSubPath() {
		return "assets/{$this->model->getTable()}_{$this->fieldname}";
	}

	public function getAssetPath() {
		if ($this->options["protected"] == true) {
			return APP_PATH . "/protected/" . $this->getAssetSubPath();
		} else {
			return APP_PATH . "/public/" . $this->getAssetSubPath();
		}
	}

	public function getFileMimeType() {
		return getMimeType($this->getAssetPath() . "/" . $this->value);
	}

	public function jqueryReady() {
		$scaled_width = ($this->options["scaledWidth"] == '') ? 300: $this->options["scaledWidth"];
		$id = $this->getID();
		$m = get_class($this->model);
		ob_start();
		echo <<<END
		$("#show-upload-field-{$this->getName()}").click(function() {
			$($(this).attr("rel")).slideDown();
			$("#file-submit-button-{$this->getName()}").show();
			return false;
		});

		var coords_{$id} = false;

		$('#image_{$id}').Jcrop({
			onSelect: function(c) {
				coords_{$id} = c;
			},
			onChange: function(c) {
				$("#{$id}-crop").fadeIn();
			}
END;
		if ($this->options["aspectRatio"] != '') {
			echo ",
				aspectRatio: {$this->options["aspectRatio"]}";
		}
		if ($this->options["minSize"] != '') {
			echo ",
				minSize: {$this->options["minSize"]}";
		}
		echo <<<END
		});
		$("#{$id}-crop").click(function() {
			var c = coords_{$id};
			location.href = SITE_URL + '/?page=admin&action=cropImage&m={$m}&{$this->model->getPrimaryKeyField()}={$this->model->pk()}&field={$this->fieldname}&x=' + c.x + '&x2=' + c.x2 + '&y=' + c.y + '&y2=' + c.y2 + '&w=' + c.w + '&h=' + c.h + '&scaled_width={$scaled_width}';
		});
		
END;
		return ob_get_clean();

	}

	public function jquery() {

	}
	public function getName() {
		return $this->fieldname ;
	}

	public function render() {
		$id = $this->getID();
		$name = $this->getName();
		$str = "";
		$idfield = $this->model->getPrimaryKeyField();

		$scaled_width = ($this->options["scaledWidth"] == '') ? 300: $this->options["scaledWidth"];

		if ($this->value != '') {
			$mime = $this->getFileMimeType();
			if (preg_match("/^image/", $mime)) {
				if ($this->options["protected"] == true) {
					$is_protected = 1;
				} else {
					$is_protected = 0;
				}
				$str .= "<img src=\"" . SITE_URL . "/?page=admin&action=image&_image={$this->getAssetSubPath()}/{$this->value}&is_protected={$is_protected}&max_width={$scaled_width}\" id=\"image_{$id}\" />
				<br />
				<span class=\"file-instructions\">You may click anywhere on the image and drag to crop out a selected portion.</span>
				<input type=\"button\" id=\"{$id}-crop\" value=\"Crop Image\" style=\"display: none;\" />";
				$descr = "image";
			} elseif (preg_match("/pdf/", $mime)) {
				$str .= "<img src=\"" . ENGINE_URL . "/images/icons/filetypes/filetype_pdf.png\" width=\"100\" />";
				$descr = "PDF Document";
			} elseif (preg_match("/video/", $mime)) {
				$str .= "<img src=\"" . ENGINE_URL . "/images/icons/filetypes/filetype_video.png\" width=\"100\" />";
				$descr = "video";
			} elseif (preg_match("/msword/", $mime) || $mime == "application/vnd.oasis.opendocument.text" || $mime == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
				$str .= "<img src=\"" . ENGINE_URL . "/images/icons/filetypes/filetype_word.png\" width=\"100\" />";
				$descr = "Word processing document";
			} elseif (preg_match("/ms-excel/", $mime) || $mime == "application/vnd.oasis.opendocument.spreadsheet" || $mime == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $mime == "text/csv") {
				$str .= "<img src=\"" . ENGINE_URL . "/images/icons/filetypes/filetype_excel.png\" width=\"100\" />";
				$descr = "spreadsheet or CSV file";
			} else {
				$descr = "file of type {$mime}";
			}

			$str .= "<br />
			<a href=\"" . SITE_URL . "/?page=admin&amp;action=removeFile&amp;m=" . get_class($this->model) . "&amp;" . $idfield . "={$this->model->{$idfield}}&amp;field={$this->fieldname}\">&raquo; Remove this {$descr}</a><br />
			<a href=\"#\" id=\"show-upload-field-{$this->getName()}\" rel=\"#{$this->getID()}\">&raquo; Replace with a different file</a><br />
			<br /><br />";
		} else {
			$str .= "<a href=\"#\" id=\"show-upload-field-{$this->getName()}\" rel=\"#{$this->getID()}\">&raquo; Click here to attach a file.</a><br />";

		}
		$str .= "<br /><input id=\"{$this->getID()}\" name=\"{$this->getName()}\" type=\"file\" size=\"30\" style=\"display: none;\"";
		if ($this->css_class != '') {
			$str .= " class=\"{$this->css_class}\"";
		}
		$str .= " />";

		return $str;

	}


}
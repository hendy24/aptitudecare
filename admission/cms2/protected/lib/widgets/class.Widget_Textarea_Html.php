<?php

class Widget_Textarea_Html extends Widget {

	public function render() {
		if ($this->options["readOnly"] == true) {
			$str = $this->value;
		} else {
			$str = "<textarea id=\"{$this->getID()}\" name=\"{$this->getName()}\" rows=\"20\" cols=\"60\" style=\"width: 400px;\"";
			if ($this->class != '') {
				$str .= " class=\"{$this->css_class} textarea_html\"";
			} else {
				$str .= " class=\"textarea_html\"";
			}
			$str .= ">";
			if ($this->value != '') {
				$str .= $this->value;
			}
			$str .= "</textarea>";
		}
		return $str;

	}
	
	public function jQueryReady() {
		$ENGINE_URL = ENGINE_URL;
		$config = $this->resolveConfig();
		echo <<<EOT
		$('#{$this->getID()}').tinymce({
			script_url: '{$ENGINE_URL}/js/jquery/tiny_mce/tiny_mce.js',
			{$config}

		});
EOT;
		
	}
	
	public function resolveConfig() {
		if ($this->options["config"] == '') {
			$config = <<<EOT
			theme: 'advanced',

			// General options
			theme : "advanced",
			plugins : "phpimage,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,iframe",

			// Theme options
			theme_advanced_buttons1 : "phpimage,iframe,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_font_sizes : "3px,6px,7px,8px,9px,10px,11px,12px,13px,14px,16px,18px,20px,22px,24px,26px,28px,30px,32px,34px,36px",
			// Example content CSS (should be your site CSS)
			//content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
			extended_valid_elements: "iframe[src|width|height|frameborder],div[class]",
			force_p_newlines: false,
			width: "450",
			entity_encoding: 'raw',

			// Allow relative URLS
			convert_urls: false,
			relative_urls: true,
			remove_script_host: false
EOT;
		} else {
			$config = $this->options["config"];
		}
	
		return $config;
	}

}
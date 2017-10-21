<?php

class PageControllerCaptcha extends PageController {
	public function index() {
		$captcha = new SimpleCaptcha();
		$captcha->CreateImage();
		exit;	
	}
}
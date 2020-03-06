<?php

class BlogPostController extends MainPageController {

	public $page = 'blog';
	public $template = 'website';


	public function index() {

		$posts = $this->loadModel('BlogPost')->fetchAll();
		pr ($posts);
	}
}
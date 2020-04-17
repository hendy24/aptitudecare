<?php

class NewsController extends MainPageController {

	public $page = 'news';
	public $template = 'website';
	// access to pages is blocked by default
	// if access is needed it will have to be allowed inside the method
	public $allow_access = false;



	public function index() {	
		smarty()->assign('title', 'News');
		$this->allow_access = true;
		$posts = $this->loadModel('BlogPost')->fetchRecentPosts();

		smarty()->assign('posts', $posts);
	}




	public function post() {
		$this->allow_access = true;
		if (isset (input()->id) && input()->id !== null) {
			$post = $this->loadModel('BlogPost', input()->id);
		} else {
			session()->setFlash('We are sorry. We could not find the blog post you are looking for', 'danger');
			$this->redirect(SITE_URL);
		}

		smarty()->assign('post', $post);
	}




	public function posts() {
		$this->allow_access = true;
		$tag = input()->url;
		$keyword = explode('/', $tag);
		$tag_name = end($keyword);

		// get posts with the search term
		$posts = $this->loadModel('BlogPost')->fetchByTag($tag_name);
		smarty()->assign('posts', $posts);
		smarty()->assign('keyword', ucfirst($tag_name));
	}


}


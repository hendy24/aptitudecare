<?php

class NewsController extends MainPageController {

	public $page = 'news';
	public $template = 'website';
	// access to pages is blocked by default
	// if access is needed it will have to be allowed inside the method
	public $allow_access = true;



	public function index() {	
		smarty()->assign('title', 'News');
		
		if (isset (input()->page_count)) {
			$page = input()->page_count;
		} else {
			$page = 1;
		}

		$posts = $this->loadModel('BlogPost')->fetchRecentPosts($page);

		$categories = $this->loadModel('BlogCategory')->countPosts();

		smarty()->assign('posts', $posts);
		smarty()->assign('categories', $categories);
	}




	public function post() {
		if (isset (input()->id) && input()->id !== null) {
			$post = $this->loadModel('BlogPost')->fetchPost(input()->id);
		} else {
			session()->setFlash('We are sorry. We could not find the blog post you are looking for', 'danger');
			$this->redirect(SITE_URL);
		}

		smarty()->assign('post', $post);
	}




	public function posts() {
		
		if (isset (input()->keyword)) {
			$keyword = input()->keyword;
			$search_type = 'tag';
			$search_word = $keyword;
		} else {
			$tag = input()->url;
			$keyword = explode('/', $tag);
			$search_type = $keyword[2];
			$search_word = end($keyword);
		}

		if (isset (input()->page_count)) {
			$page = input()->page_count;
		} else {
			$page = 1;
		}

		
		

		// get posts with the search term
		if ($search_type == 'tag') {
			$posts = $this->loadModel('BlogPost')->fetchByTag($search_word);
		} else {
			$posts = $this->loadModel('BlogPost')->fetchByCategory($search_word);
		}

		$categories = $this->loadModel('BlogCategory')->countPosts();

		
		smarty()->assign('posts', $posts);
		smarty()->assign('keyword', ucfirst($search_word));
		smarty()->assign('categories', $categories);

	}


}


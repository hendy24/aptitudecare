<?php

class BlogController extends MainPageController {

	public $module = 'Blog';
	public $page = 'blog';
	public $template = 'main';
	// access to pages is blocked by default
	// if access is needed it will have to be allowed inside the method
	public $allow_access = false;



	public function index() {
		if (isset (input()->page_count)) {
			$page_count = input()->page_count;
		} else {
			$page_count = 1;
		}
		$posts = $this->loadModel('BlogPost')->fetchRecentPosts($page_count, 10, false);

		smarty()->assign('blogPosts', $posts);
	}


	public function edit() {
		if (!auth()->getRecord()) {
			$this->redirect(SITE_URL);
		}

		// if there is an id in the url then we are editing an existing post
		if (isset(input()->id) && input()->id !== null) {
			$post = $this->loadModel('BlogPost', input()->id);
		} else {
			$post = $this->loadModel('BlogPost');
		}

		// get categories
		$categories = $this->loadModel('BlogCategory')->fetchAll();

		// get tags
		$tags = $this->loadModel('BlogTag')->fetchAll();

		// get tags assigned to this post
		$blogTags = $this->loadModel('BlogPostTagLink')->fetchExistingTags($post->id);

		// assign the object to smarty to use in the view page
		smarty()->assign('post', $post);
		smarty()->assign('categories', $categories);
		smarty()->assign('tags', $tags);
		smarty()->assign('blogTags', $blogTags);
	}





	public function save() {

		if (isset (input()->id)) {
			$post = $this->loadModel('BlogPost', input()->id);
		} else {
			$post = $this->loadModel('BlogPost');
		}
		
		if (input()->title == null) {
			session()->setFlash('Please enter a post title', 'danger');
			$this->redirect(input()->current_url);
		} else {
			$post->title = input()->title;
		}

		$post->content = input()->content;
		$post->user_id = auth()->getRecord()->id;

		if (isset (input()->published)) {
			if ($post->date_published == null) {
				$post->date_published = mysql_date();
			}
		} else {
			$post->date_published = null;
		}

		if (input()->category != null) {
			$post->category_id = input()->category;
		}

		// create a cover image object
		$cover_image = $this->loadModel('BlogCoverImage');
		// upload the cover image
		if ($_FILES['cover_image']['name'] != null) {
			foreach ($_FILES as $k => $v) {
				if ($k !== 'files') {
					$keyname = $k;
				}
				 
			}

			$cover_image->filename = $_FILES[$keyname]["name"];

			if ($this->upload_file($_FILES, $keyname)) {
				$cover_image->save();

				// add the cover image id to the blog post
				$post->cover_image_id = $cover_image->id;
			}
		}

		if ($post->save()) {
			// delete all existing tags first
			$this->loadModel('BlogPostTagLink')->deleteTags($post->id);
			if (!empty (input()->blog_tags)) {
				foreach (input()->blog_tags as $id) {
					$postTag = $this->loadModel('BlogPostTagLink');
					$postTag->blog_post_id = $post->id;
					$postTag->blog_tag_id = $id;
					$postTag->save();
				}
			}

			session()->setFlash('The post was saved', 'success');
			$this->redirect(SITE_URL . DS . '?module=Blog&page=blog');			
		} else {
			session()->setFlash('The post was not saved', 'danger');
			$this->redirect(input()->current_url);
		}

	}

	protected function delete_post() {
		if (isset (input()->id) && input()->id !== null) {
			$post = $this->loadModel('BlogPost', input()->id);

			if ($post->delete()) {
				session()->setFlash('The blog post was deleted', 'success');
				return true;
			} else {
				session()->setFlash('Could not delete the post', 'danger');
				return false;
			}
		} else {
			session()->setFlash('Could not delete the post', 'danger');
			return false;
		}


	}
}


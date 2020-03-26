<?php


	class BlogTagController extends MainPageController {

		public $template = 'empty';

		public function get_tags() {
			$tags = $this->loadModel('BlogTag')->fetchAll();
			json_return($tags);
		}


		public function get_existing_tags() {
			// get post
			$tags = $this->loadModel('BlogTag')->fetchExistingTags(input()->post_id);
			json_return($tags);
		}



		public function add_tags() {
			// get the post from the post_id
			if (input()->post_id !== null) {
				$post = $this->loadModel('BlogPost', input()->post_id);
			} 

			// check that tags have been entered
			// take tags from a string into an array
			if (input()->name != null) {
				$tags_array = explode(',', input()->name);
			}
			
			// fetch all existing tags
			$existing_tags = $this->loadModel('BlogTag')->fetchAll();
			$this->loadModel('BlogPostTagLink')->deleteExisting(input()->post_id);

			
			// check if entered tags match any existing tags
			foreach ($tags_array as $t) {
				$bptLink = $this->loadModel('BlogPostTagLink');
				$no_match = true;
				foreach ($existing_tags as $et) {
					// if the names don't match, this is a new tag
					if ($t == $et->name) {
						// set the blog_tag_id to be the existing tag id
						$bptLink->blog_tag_id = $et->id;
						$no_match = false;
						break;
					} 
				}
				if ($no_match) {
					$tag = $this->loadModel('BlogTag');
					$tag->name = $t;
					$tag->save();
					// set the blog_tag_id to be the new tag id
					$bptLink->blog_tag_id = $tag->id;						
				}

				// set the blog_post_id
				$bptLink->blog_post_id = $post->id;

				// save the link tables
				$bptLink->save();
			}

			exit;

		}


		public function delete_tags() {
			// delete all currently existing tags for the blog post
			if (input()->type == 'post') {
							
			}

		}





	}
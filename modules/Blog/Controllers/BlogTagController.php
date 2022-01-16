<?php


	class BlogTagController extends MainPageController {

		public $template = 'empty';

		public function get_tags() {
			$tags = $this->loadModel('BlogTag')->fetchAll();
			json_return($tags);
		}

		public function create_tag() {
			if (input()->name != null) {
				$tag = $this->loadModel('BlogTag');
				$tag->name = input()->name;

				if ($tag->save()) {
					json_return ($tag);
				}
			}

			return false;
		}

	}
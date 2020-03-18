<?php

class BlogPost extends AppModel {

	protected $table = 'blog_post';

	public function fetchRecentPosts($page = false) {

		// Get the total number of posts
		$sql = "SELECT count(id) AS posts FROM {$this->tableName()} WHERE date_published IS NOT NULL;";
		$count = $this->fetchOne($sql);


		$params = array();

		$sql = "SELECT * FROM {$this->tableName()} WHERE date_published IS NOT NULL ORDER BY date_published DESC";

		$pagination = new Paginator();
		$pagination->default_ipp = 5;
		$pagination->items_total = $count->posts;
		return $pagination->paginate($sql, $params, $this, $page, 5);

	}
}
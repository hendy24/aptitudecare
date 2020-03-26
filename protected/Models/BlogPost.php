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


	public function fetchByKeyword($keyword) {
		$tag = $this->loadTable('BlogTag');
		$tagLink = $this->loadTable('BlogPostTagLink');

		// $sql = "SELECT post.id AS post_id, post.public_id, post.title, post.content, tag.id AS tag_id, tag.name AS tag_name FROM {$this->tableName()} AS post INNER JOIN {$tagLink->tableName()} AS tagLink ON tagLink.blog_post_id = post.id INNER JOIN {$tag->tableName()} AS tag on tag.id = tagLink.blog_tag_id WHERE tag.name LIKE ':keyword' AND post.date_published IS NOT NULL ORDER BY post.date_published ASC";
		$params[':keyword'] = '%' . $keyword . '%';

		return $this->fetchAll();
	}
}
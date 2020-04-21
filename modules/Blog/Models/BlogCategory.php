<?php

class BlogCategory extends AppModel {

	protected $table = 'blog_category';

	public function countPosts() {
		$blog_post = $this->loadTable('BlogPost');

		$sql = "SELECT {$this->tableName()}.name, count(*) AS post_count FROM {$this->tableName()} INNER JOIN {$blog_post->tableName()} ON {$this->tableName()}.id = {$blog_post->tableName()}.category_id GROUP BY {$this->tableName()}.name";

		return $this->fetchAll($sql);
	}

}
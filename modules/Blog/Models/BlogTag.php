<?php


	class BlogTag extends AppModel {

		protected $table = 'blog_tag';


		public function fetchTags($blog_id) {
			$tagLink = $this->loadTable('BlogPostTagLink');


			$sql = "SELECT * FROM {$this->tableName()} INNER JOIN blog_post_tag_link ON {$this->tableName()}.id = {$tagLink->tableName()}.blog_tag_id WHERE {$tagLink->tableName()}.blog_post_id = :id";
			$params[":id"] = $blog_id;

			return $this->fetchAll($sql, $params);
		}


		public function fetchExistingTags($blog_id) {
			$tagLink = $this->loadTable('BlogPostTagLink');
			$blogPost = $this->loadTable('BlogPost');

			$sql = "SELECT {$this->tableName()}.id, {$this->tableName()}.name FROM {$this->tableName()} INNER JOIN {$tagLink->tableName()} ON {$this->tableName()}.id = {$tagLink->tableName()}.blog_tag_id INNER JOIN {$blogPost->tableName()} ON {$blogPost->tableName()}.id = {$tagLink->tableName()}.blog_post_id WHERE {$blogPost->tableName()}.public_id = :id";
			$params[":id"] = $blog_id;

			return $this->fetchAll($sql, $params);

		}
	}
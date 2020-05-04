<?php

class BlogPost extends AppModel {

	protected $table = 'blog_post';

	public function fetchRecentPosts($page = false, $items_per_page = 5, $published = true) {

		$images = $this->loadTable('BlogCoverImage');

		// Get the total number of posts
		$sql = "SELECT count(id) AS posts FROM {$this->tableName()} WHERE date_published IS NOT NULL;";
		$count = $this->fetchOne($sql);


		$params = array();

		$sql = "SELECT 
					post.id, 
					post.public_id,
					images.filename,
					post.title,
					post.content,
					post.date_published";
					if (!$published) {
						$sql .= ", post.datetime_created";
					}
		$sql .= " FROM {$this->tableName()} AS post
				LEFT JOIN {$images->tableName()} AS images
					ON images.id = post.cover_image_id";
		if ($published) {
			$sql .= " WHERE date_published IS NOT NULL ORDER BY date_published DESC";
		} else {
			$sql .= " ORDER BY datetime_created DESC ";
		}
		
		$pagination = new Paginator();
		$pagination->default_ipp = $items_per_page;
		$pagination->items_total = $count->posts;
		return $pagination->paginate($sql, $params, $this, $page);

	}

	public function fetchPost($id) {
		$images = $this->loadTable('BlogCoverImage');
		
		$sql = "SELECT 
					{$this->tableName()}.id, 
					{$this->tableName()}.public_id,
					{$images->tableName()}.filename,
					{$this->tableName()}.title,
					{$this->tableName()}.content,
					{$this->tableName()}.date_published
				FROM {$this->tableName()} 
				LEFT JOIN {$images->tableName()} 
					ON {$images->tableName()}.id = {$this->tableName()}.cover_image_id 
				WHERE {$this->tableName()}.public_id = :id";

		$params[":id"] = $id;

		return $this->fetchOne($sql, $params);

	}


	public function fetchByTag($keyword, $page = false) {
		$tag = $this->loadTable('BlogTag');
		$tagLink = $this->loadTable('BlogPostTagLink');
		$images = $this->loadTable('BlogCoverImage');

		$params[":keyword"] = "%{$keyword}%";

		// Get the total number of posts
		$sql = "SELECT count(post.id) AS posts 
				FROM {$this->tableName()} AS post 
				INNER JOIN {$tagLink->tableName()} AS tagLink 
					ON tagLink.blog_post_id = post.id 
				INNER JOIN {$tag->tableName()} AS tag 
					ON tag.id = tagLink.blog_tag_id 
				WHERE tag.name LIKE :keyword 
					AND post.date_published IS NOT NULL"; 

		$count = $this->fetchOne($sql, $params);

		$sql = "SELECT 
					post.id AS post_id, 
					post.public_id, 
					post.title, 
					post.content, 
					post.date_published, 
					tag.id AS tag_id, 
					tag.name AS tag_name,
					img.filename AS filename
				FROM {$this->tableName()} AS post 
				INNER JOIN {$tagLink->tableName()} AS tagLink 
					ON tagLink.blog_post_id = post.id 
				INNER JOIN {$tag->tableName()} AS tag 
					ON tag.id = tagLink.blog_tag_id 
				LEFT JOIN {$images->tableName()} AS img 
					ON img.id =post.cover_image_id 
				WHERE tag.name LIKE :keyword 
					AND post.date_published IS NOT NULL 
				ORDER BY post.date_published DESC";

		

		$pagination = new Paginator();
		$pagination->default_ipp = 5;
		$pagination->items_total = $count->posts;
		return $pagination->paginate($sql, $params, $this, $page);

	}


	public function fetchByCategory($keyword, $page = false) {
		$category = $this->loadTable('BlogCategory');
		$images = $this->loadTable('BlogCoverImage');

		// Get the total number of posts
		$sql = "SELECT count(category.id) as posts
			FROM {$category->tableName()} AS category
			INNER JOIN {$this->tableName()} AS post 
				ON category.id = post.category_id 
			WHERE category.name = :name";
		$params[":name"] = $keyword;

		$count = $this->fetchOne($sql, $params);


		$sql = "SELECT 
				post.id AS post_id,
				post.public_id,
				post.title,
				post.content,
				post.date_published,
				img.filename
			FROM {$this->tableName()} AS post 
			INNER JOIN {$category->tableName()} AS category 
				ON category.id = post.category_id 
			LEFT JOIN {$images->tableName()} AS img 
				ON img.id = post.cover_image_id
			WHERE category.name = :name
			ORDER BY date_published DESC";


		$pagination = new Paginator();
		$pagination->default_ipp = 5;
		$pagination->items_total = $count->posts;
		return $pagination->paginate($sql, $params, $this, $page, 5);
	}
}
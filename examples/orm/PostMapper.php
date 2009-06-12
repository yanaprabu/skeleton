<?php

require_once('A/Orm/DataMapper.php');
require_once('A/Orm/DataMapper/Mapping.php');

class PostMapper extends A_Orm_DataMapper	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
	}

	public function getById($id)	{
		$stmt = $this->db->prepare('SELECT ' . join(', ', $this->getColumns()) . ' FROM ' . $this->table . ' WHERE id = :id');
		$stmt->bindValue (':id', $id);
		$stmt->execute();
		return $this->load($stmt->fetch(PDO::FETCH_ASSOC));
	}

	public function getAll()	{
		$stmt = $this->db->prepare('SELECT ' . join(', ', $this->getColumns()) . ' FROM ' . $this->table);
		$stmt->execute();
		$posts = array();
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $post)	{
			$posts[$post['id']] = $this->load($post);
		}
		return $posts;
	}

}
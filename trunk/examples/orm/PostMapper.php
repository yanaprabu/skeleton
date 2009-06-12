<?php

require_once('A/Orm/DataMapper.php');
require_once('A/Orm/DataMapper/Mapping.php');

class PostMapper extends A_Orm_DataMapper	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id')->setKey();
		$this->map('author_id');
		$this->map('title');
		$this->map('body');
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

	public function save($post)	{
		if ($post->getId())	{
			$this->update($post);
		} else {
			$this->insert($post);
		}
	}

	public function insert($post)	{
		$keys = array();
		$values = array();
		foreach ($this->mappings as $mapping)	{
			if ($array = $mapping->loadArray($post))	{
				if (!$mapping->isKey())	{
					$keys[] = key($array) . ' = ?';
					$values[] = current($array);
				}
			}
		}
		$stmt = $this->db->prepare ('INSERT INTO ' . $this->table . ' SET ' . join(',', $keys));
		$stmt->execute($values);
	}

	public function update($post)	{
		$keys = array();
		$values = array();
		foreach ($this->mappings as $mapping)	{
			if ($array = $mapping->loadArray($post))	{
				if ($mapping->isKey())	{
					$pkey = key($array);
					$pkey_value = current($array);
				} else {
					$keys[] = key($array) . ' = ?';
					$values[] = current($array);
				}
			}
		}
		$values[] = $pkey_value;
		$stmt = $this->db->prepare ('UPDATE ' . $this->table . ' SET ' . join(',', $keys) . ' WHERE ' . $pkey . ' = ?');
		$stmt->execute($values);
	}

}
<?php

class client extends A_Model
{

	public function __construct()
	{
		$this->set('name', 'Guest' . rand(0, 100));
		$this->set('joined_time', time());
		$this->set('cid', base_convert(rand(1000, 9999), 10, 16));
	}

	public function serialize()
	{
		return (object) array(
			'name' => $this->get('name'),
			'joined_time' => $this->get('joined_time'),
			'cid' => $this->get('cid')
		);
	}

	public function deserialize($data)
	{
		$this->set('name', $data->name);
	}
}
<?php

class model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = new database();
	}
	
	public function encrypt($string)
	{
		return md5($string);
	}

}

?>
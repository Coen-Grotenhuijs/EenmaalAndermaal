<?php 

class database
{
	private static $c;
	private $servername;
	private $connectioninfo = array();
	
	public function __construct()
	{
		if(empty($this->c))
		{
			$settings = new settings();
			$this->servername = $settings->getSetting('database_servername');
			$this->connectioninfo = array(  "Database"  => $settings->getSetting('database_database'),
							"UID"       => $settings->getSetting('database_username'),
							"PWD"       => $settings->getSetting('database_password'));
			$this->c = sqlsrv_connect($this->servername, $this->connectioninfo) or die("Kon niet verbinden met de database.");
		}
	}
	
	public function query($query)
	{
		$return = sqlsrv_query($this->c, $query);
		return $return;
	}
	
	public function fetch($result)
	{
		return sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	}
	
	public function fetchAll($result)
	{
		$return = array();
		while($r = $this->fetch($result)) $return[] = $r;
		return $return;
	}
	
	public function fetchQuery($query)
	{
		return $this->fetch($this->query($query));
	}
	
	public function fetchQueryAll($query)
	{
		return $this->fetchAll($this->query($query));
	}
	
	public function __destruct()
	{
		if(!empty($this->c))
		{
			sqlsrv_close($this->c);
		}
	}
}

?>
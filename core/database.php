<?php 

class database
{
        
        private static $total = 0;
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
							"PWD"       => $settings->getSetting('database_password'),
                                                        "CharacterSet" => "UTF-8");
			$this->c = sqlsrv_connect($this->servername, $this->connectioninfo) or die("Kon niet verbinden met de database.");
		}
	}
	
	public function query($query)
	{
                $millis = microtime(true);
		$return = sqlsrv_query($this->c, $query);
//                echo (microtime(true)-$millis).', '.$millis.': '.$query."<br>";
                $this->total += microtime(true)-$millis;
                
//                print_r(sqlsrv_errors());
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
        
        public function insertGetId($query)
        {
                $query = $query."; SELECT SCOPE_IDENTITY()";
                $arrParams[]="1"; 
                $arrParams[]="2"; 
		$resource = sqlsrv_query($this->c, $query, $arrParams);
                sqlsrv_next_result($resource); 
                sqlsrv_fetch($resource); 
                return sqlsrv_get_field($resource, 0);
        }
	
	public function __destruct()
	{
//                echo '<br><br>Totaal '.get_class().': '.$this->total.'<br><br>';
		if(!empty($this->c))
		{
			sqlsrv_close($this->c);
		}
	}
}

?>
<?php

class settings
{
	private static $file;
	
	public function __construct()
	{
		if(empty($this->file)) $this->file = file_get_contents('../settings.cfg');
	}
	
	public function getPart($part)
	{
		$data = array();
		
		$lines = explode("\n", $this->file);
		$reading = false;
		foreach($lines as $key=>$value)
		{
			if($reading)
			{
				if(substr($value, 0, 1) == "[") break;
				$part = explode("=", $value);
				$index = array_shift($part);
				$data[$index] = implode("=", $part);
			}

			if(strtoupper(trim($value)) == "[".strtoupper($part)."]") $reading = true;
			
		}
		
		return $data;
	}
	
	public function getSetting($setting)
	{
		$name = explode("_", $setting);
		$data = $this->getPart($name[0]);
		if(!empty($data[$name[1]])) return $data[$name[1]];
		return;
	}

}




?>
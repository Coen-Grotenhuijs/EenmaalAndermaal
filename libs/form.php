<?php

class form
{
	private $valid = true;
	private $errors = array();
	private $locks = array();
	private $data;
	
	private $error;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function check($parameter, $checks)
	{
		foreach($checks as $key=>$content)
		{
			if(is_array($content))
			{
				$this->error = $content[count($content)-1];
				$value = $content[0];
			}
			else
			{
				$this->error = '';
				$value = $content;
			}
			switch($key)
			{
				case 'not null':
					if($value=='true' || $value=='1')
					{
						if(strlen($this->data[$parameter])<1)
						{
							$this->adderror($parameter, "Dit veld mag niet leeg zijn.");
						}
					}
					break;
				case 'length':
					$arguments = explode('-',$value);
					if(strlen($this->data[$parameter])<$arguments[0])
					{
						$this->adderror($parameter, "De lengte van dit veld moet minimaal ".$arguments[0]." tekens zijn.");
					}
					elseif(strlen($this->data[$parameter])>$arguments[1])
					{
						$this->adderror($parameter, "De lengte van dit veld mag maximaal ".$arguments[1]." tekens zijn.");
					}
					break;
				case 'equals':
					if($this->data[$parameter]!=$value)
					{
						$this->adderror($parameter, "De waarden komen niet overeen.");
					}
					break;
				case 'null':
					if(!empty($value))
					{
						$this->adderror($parameter, "Deze waarde is niet uniek in de database.");
					}
					break;
				case 'not empty':
					if(empty($value))
					{
						$this->adderror($parameter, "Niet gevonden in de database");
					}
					break;
				case 'stringcheck':
					if(!$this->stringcheck($this->data[$parameter], $value))
					{
						$this->adderror($parameter, "Er zijn ongeldige tekens gebruikt.");
					}
					break;
                                case 'isnumber':
                                        if(!$this->stringcheck(str_replace(",",".",$this->data[$parameter]),'0-9,.'))
                                        {
						$this->adderror($parameter, "Er zijn ongeldige tekens gebruikt.");
                                        }
                                        break;
                                case 'isemail':
                                        if(!filter_var($this->data[$parameter], FILTER_VALIDATE_EMAIL))
                                        {
                                                $this->adderror($parameter, "Ongeldigd e-mailadres");
                                        }
                                        break;
                                case 'bigger':
                                        if(str_replace(",",".",$this->data[$parameter])<$value)
                                        {
                                                $this->adderror($parameter, "Te klein getal.");
                                        }
			}
		}
	}
	
	private function adderror($parameter, $error)
	{
		if(!empty($this->error)) $error = $this->error;
		if(empty($this->locks[$parameter])) $this->errors[$parameter][] = $error;
		$this->lock($parameter);
		$this->valid = false;
	}
	
	private function lock($parameter)
	{
		$this->locks[$parameter] = true;
	}
	
	public function geterrors()
	{
		$return = array();
		foreach($this->errors as $key=>$array)
		{
			$key = 'error_'.$key;
			$return[$key] = '<span class="form_error">';
			foreach($array as $error)
			{
				$return[$key] .= $error.'<br \>';
			}
			$return[$key] .= '</span>';
		}
		return $return;
	}
	
	public function getclasses()
	{
		$return = array();
		foreach($this->errors as $key=>$array)
		{
			$return['class_'.$key] = 'class="error"';
		}
		return $return;
	}
	
	public function valid()
	{
		return $this->valid;
	}
	
	private function stringcheck($string, $options)
	{
		$option = explode(",",$options);
                $allowed = array();
                
		foreach($option as $key=>$value)
		{
			if(strlen($value)==1) $allowed[$key] = $value;
			else $allowed[$key] = explode("-", trim($value));
		}

                for($i=0;$i<strlen($string);$i++)
		{
			$allow = false;
			foreach($allowed as $key=>$value)
			{
				if(is_array($value))
				{
					if(ord($string[$i])>=ord($value[0]) && ord($string[$i])<=ord($value[1])) $allow = true;
				}
				elseif($value==$string[$i]) $allow = true;
			}
			if($allow==false) return false;
		}
		
		return true;
	}
}

?>
<?php

class view
{
    private $output = '';
    
    function __construct()
    {
	
    }
    
    public function load($file, $template = '')
    {
        if(!empty($template))
        {
            ob_start();
            include("view/".$file.".html");
            $content = ob_get_contents();
            ob_end_clean();
            $this->output = str_replace('{'.  strtoupper($template).'}',$content,$this->output);
        }
        else
        {
            $this->output .= file_get_contents("view/".$file.".html");
        }
            
    }
    
    public function replace($template, $value)
    {
        if(!is_array($value)) $this->output = str_replace('{'.strtoupper($template).'}', $value, $this->output);
    }
	
	public function replace_array($array)
	{
		foreach($array as $key=>$value)
		{
			if(!is_array($value)) $this->replace($key, $value);
		}
	}
	
	public function cleanup($template)
	{
		$this->output = preg_replace('/\{'.$template.'(\w*)\}/', '', $this->output);
	}
    
    public function __destruct()
    {
        echo $this->output;
    }
}

?>
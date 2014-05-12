<?php

class zoekControl extends control
{
	public function run()
	{
                $data = $this->rubriekenModel->getRubrieken();
                $string = "";
                foreach($data as $key=>$value)
                {
                        $string .= print_r($value,true)."<br>";
                }
		$this->replaceView('content', $string);
	}
}

?>
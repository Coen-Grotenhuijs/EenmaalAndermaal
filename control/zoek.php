<?php

class zoekControl extends control
{
	public function run()
	{
                $data = $this->rubriekenModel->getRubrieken();
                $string = "";
                foreach($data as $key=>$value)
                {
                        $string .= $value."<br>";
                }
		$this->replaceView('content', $string);
	}
}

?>
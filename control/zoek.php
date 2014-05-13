<?php

class zoekControl extends control
{
	public function run()
	{
                $page = 1;
                $perpage = 10;
                if(!empty($this->get['page']))
                {
                        $page = $this->get['page'];
                }
                if(!empty($this->get['perpage']))
                {
                        $perpage = $this->get['perpage'];
                }
                $data = $this->rubriekenModel->zoek($this->get['zoekveld'],$this->get['rubriek'], $page, $perpage);
                $string = "";
                foreach($data as $key=>$value)
                {
                        $string .= print_r($value,true)."<br>";
                }
		$this->replaceView('content', $string);
	}
}

?>
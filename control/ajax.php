<?php

class ajaxControl extends control
{
	public function run()
	{
		if(empty($this->get['do']))
                {
                        die();
                }
                else
                {
                        switch($this->get['do'])
                        {
                                case 'zoek':
                                        $this->zoek();
                                        break;
                        }
                }
                        
	}
        
        private function zoek()
        {
                $this->loadView('ajax/zoek');
                
                $this->loadModel('relevantie');
                
                $resultaten_relevantie = $this->relevantieModel->getAjax();
                
                $ids = array();
                
                foreach($resultaten_relevantie as $key=>$value)
                {
                        $ids[] = $value['Voorwerpnummer'];
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->rubriekenModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('zoek/resultaat','next_resultaat');
                        $this->replaceView('resultaat_image',$file);
                        $this->replaceView('resultaat_tag',$value['Titel']);
                        $this->replaceView('resultaat_prijs',$prijs);
                        $this->replaceView('resultaat_nummer', $value['Voorwerpnummer']);
                        $timer = new Timer();
                        $time = $timer->getTimestamp($value['Looptijdeindedag'], $value['Looptijdeindetijdstip']);
                        $this->replaceView('timer_class',$timer->setTimer($time));
                }
                
                if($this->logged_in) $this->relevantieModel->addSearch($ids);
                
                $this->replaceView('next_resultaat','');
        }
}

?>
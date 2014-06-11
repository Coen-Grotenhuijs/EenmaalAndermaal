<?php

class zoekControl extends control
{
	public function run()
	{
                Timer::resetCounter();

                $this->loadModel('relevantie');
                
		$this->loadView('zoek/zoek','content');
                $this->replaceView('title','Zoek');
                
                $zoek = '';
                $rubriek = 0;
                $page = 1;
                $perpage = 10;
                
                if(!empty($this->get['zoekveld']))
                {
                        $zoek = $this->get['zoekveld'];
                }
                if(!empty($this->get['rubriek']))
                {
                        $rubriek = $this->get['rubriek'];
                }
                if(!empty($this->get['page']))
                {
                        $page = $this->get['page'];
                }
                if(!empty($this->get['perpage']))
                {
                        $perpage = $this->get['perpage'];
                }
                
                
                $resultaten = $this->rubriekenModel->zoek($zoek,$rubriek);
                
                $resultaten_relevantie = $this->relevantieModel->getZoekRelevantie($resultaten, $zoek, $rubriek, $page, $perpage);
                
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

                if(empty($resultaten_relevantie))
                {
                        $this->loadView('zoek/geenresultaten', 'next_resultaat');
                }
                $this->replaceView('next_resultaat','');
                
                $alleRubrieken = array('Nummer'=>'0', 'Naam'=>'Alle rubrieken', 'Subs'=>null);
                
                $rubrieken = $this->rubriekenModel->getRubriekenArray();
                
                array_unshift($rubrieken, $alleRubrieken);
                
                $this->replaceView('rubrieken_overzicht',$this->parseRubrieken($rubrieken));
                
                $this->replaceView('rubrieken_active_'.$rubriek, "class='active'");
                
                $this->view->cleanup('rubrieken_active');
	}
        
        private function parseRubrieken($data)
        {
                $string = '<ul class="rubriekenlist">';
                foreach($data as $key=>$value)
                {
                        $string .= '<li {RUBRIEKEN_ACTIVE_'.$value['Nummer'].'}><a href="/zoek.php?rubriek='.$value['Nummer'].'"><span>'.$value['Naam'].'</span></a>';
                        if(is_array($value['Subs']))
                        {
                                $string .= $this->parseRubrieken($value['Subs']);
                        }
                        $string .= '</li>';
                }
                $string .= '</ul>';
                return $string;
        }
}

?>
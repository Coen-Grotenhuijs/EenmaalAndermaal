<?php

class zoekControl extends control
{
	public function run()
	{
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
                
                foreach($resultaten_relevantie as $key=>$value)
                {
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->rubriekenModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('zoek/resultaat','next_resultaat');
                        $this->replaceView('resultaat_image',$file);
                        $this->replaceView('resultaat_tag',$value['Titel']);
                        $this->replaceView('resultaat_prijs',$prijs);
                        $this->replaceView('resultaat_time','?');
                }

                $this->replaceView('next_resultaat','');
                
                $this->replaceView('rubrieken_overzicht',$this->parseRubrieken($this->rubriekenModel->getRubriekenArray()));
	}
        
        private function parseRubrieken($data)
        {
                $string = '<ul>';
                foreach($data as $key=>$value)
                {
                        $string .= '<li><a href="/zoek?rubriek='.$value['Nummer'].'">'.$value['Naam'].'</a></li>';
                        if(is_array($value['Subs']))
                        {
                                $string .= $this->parseRubrieken($value['Subs']);
                        }
                }
                $string .= '</ul>';
                return $string;
        }
}

?>
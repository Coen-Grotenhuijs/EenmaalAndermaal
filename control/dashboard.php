<?php

class dashboardControl extends control
{
	public function run()
	{
                if(!$this->dashboardModel->getLoggedIn())
                {
                        header('Location: home');
                }
                
		$this->loadView('dashboard/dashboard','content');
                $this->replaceView('title','Dashboard');
                
                $biedingen = $this->dashboardModel->getBiedingen();
                foreach($biedingen as $key=>$value)
                {
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->dashboardModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('dashboard/bieding','next_bieding');
                        $this->replaceView('bieding_image',$file);
                        $this->replaceView('bieding_tag',$value['Titel']);
                        $this->replaceView('bieding_prijs',$prijs);
                        $this->replaceView('bieding_nummer', $value['Voorwerpnummer']);
                        $timer = new Timer();
                        $time = $timer->getTimestamp($value['Looptijdeindedag'], $value['Looptijdeindetijdstip']);
                        $this->replaceView('timer_class',$timer->setTimer($time));
                }

                $this->replaceView('next_bieding','');
                
                $voorwerpen = $this->dashboardModel->getVoorwerpen();
                foreach($voorwerpen as $key=>$value)
                {
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->dashboardModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('dashboard/voorwerp','next_voorwerp');
                        $this->replaceView('voorwerp_image',$file);
                        $this->replaceView('voorwerp_tag',$value['Titel']);
                        $this->replaceView('voorwerp_prijs',$prijs);
                        $this->replaceView('voorwerp_nummer', $value['Voorwerpnummer']);
                        $timer = new Timer();
                        $time = $timer->getTimestamp($value['Looptijdeindedag'], $value['Looptijdeindetijdstip']);
                        $this->replaceView('timer_class',$timer->setTimer($time));
                }

                $this->replaceView('next_voorwerp','');

                $suggesties = $this->dashboardModel->getSuggesties();
                foreach($suggesties as $key=>$value)
                {
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->dashboardModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('dashboard/suggestie','next_suggestie');
                        $this->replaceView('suggestie_image',$file);
                        $this->replaceView('suggestie_tag',$value['Titel']);
                        $this->replaceView('suggestie_prijs',$prijs);
                        $this->replaceView('suggestie_nummer', $value['Voorwerpnummer']);
                        $timer = new Timer();
                        $time = $timer->getTimestamp($value['Looptijdeindedag'], $value['Looptijdeindetijdstip']);
                        $this->replaceView('timer_class',$timer->setTimer($time));
                }

                $this->replaceView('next_suggestie','');
                
                $profiel = $this->dashboardModel->getProfielgegevens();
                
                $this->replaceView('profiel_naam', $profiel['Voornaam'].' '.(empty($profiel['Tussenvoegsel']) ? '' : $profiel['Tussenvoegsel'].' ').$profiel['Achternaam']);
                $this->replaceView('profiel_adres', $profiel['Adresregel1'].(trim($profiel['Adresregel2'])=='' ? '' : '<br>'.$profiel['Adresregel2']));
                $this->replaceView('profiel_postcode', $profiel['Postcode']);
                $this->replaceView('profiel_woonplaats', $profiel['Plaatsnaam']);
                $this->replaceView('profiel_land', $profiel['Land']);
                
                if($this->dashboardModel->getVerkoper())
                {
                        $this->loadView('dashboard/feedback','feedback');

                        $feedback = $this->dashboardModel->getScore();

                        $score = 0;

                        foreach($feedback as $key=>$value)
                        {
                                switch($value['Feedbacksoort'])
                                {
                                        case -1:
                                                $this->replaceView('beoordeling_negatief', $value['Aantal']);
                                                $score -= $value['Aantal'];
                                                break;
                                        case 0:
                                                $this->replaceView('beoordeling_neutraal', $value['Aantal']);
                                                break;
                                        case 1:
                                                $this->replaceView('beoordeling_positief', $value['Aantal']);
                                                $score += $value['Aantal'];
                                                break;
                                }
                        }
                        $this->replaceView('beoordeling_negatief', 0);
                        $this->replaceView('beoordeling_neutraal', 0);
                        $this->replaceView('beoordeling_positief', 0);
                        $this->replaceView('beoordeling_score', $score);
                }
                else
                {
                        $this->replaceView('feedback', '');
                }
        }
        
}

?>
<?php

class registrerenControl extends control
{
	public function run()
	{
                $this->replaceView('title','Registreren');
                
                if($this->logged_in)
                {
                        header('Location: home');
                }
                        
                if(empty($_SESSION['registratiestap'])) $_SESSION['registratiestap'] = 1;
                
		$this->loadView('registreren/registreren'.$_SESSION['registratiestap'],'content');

                if(empty($_SESSION['registratie']))
                {
                        $_SESSION['registratie']  = array();
                }
                
                if(!empty($this->post['next']) || !empty($this->post['previous']))
                {
                        $this->saveData($_SESSION['registratiestap']);
                }
                
                if(!empty($this->post['next']))
                {
                        $form = $this->getForm($_SESSION['registratiestap']);
                        
                        if($form->valid())
                        {
                                if($_SESSION['registratiestap']<4) $_SESSION['registratiestap']++;
                                header('Location: registreren');
                        }
                        if($_SESSION['registratiestap']==4)
                        {
                                $this->register($_SESSION['registratie']);
                        }
                }
                elseif(!empty($this->post['previous']))
                {
                        if($_SESSION['registratiestap']>1)
                        {
                                $_SESSION['registratiestap']--;
                                header('Location: registreren');
                        }
                }
	}
        
        private function saveData($stap)
        {
                switch($stap)
                {
                        case 1:
                                $_SESSION['reg_email'] = $this->post['email'];
                                $_SESSION['reg_gebruikersnaam'] = $this->post['gebruikersnaam'];
                                $_SESSION['reg_wachtwoord'] = $this->post['wachtwoord'];
                                $_SESSION['reg_herh_wachtwoord'] = $this->post['herh_wachtwoord'];
                                $_SESSION['reg_vraag'] = $this->post['vraag'];
                                $_SESSION['reg_antwoord'] = $this->post['antwoord'];
                                
                                break;
                        case 2:
                                
                                $_SESSION['reg_voornaam'] = $this->post['voornaam'];
                                $_SESSION['reg_achternaam'] = $this->post['achternaam'];
                                $_SESSION['reg_straat'] = $this->post['straat'];
                                $_SESSION['reg_huisnummer'] = $this->post['huisnummer'];
                                $_SESSION['reg_woonplaats'] = $this->post['woonplaats'];
                                $_SESSION['reg_postcode'] = $this->post['postcode'];
                                $_SESSION['reg_land'] = $this->post['land'];
                                $_SESSION['reg_geboortedatum'] = $this->post['geboortedatum'];
                                $_SESSION['reg_tel1'] = $this->post['tel1'];
                                $_SESSION['reg_tel2'] = $this->post['tel2'];
                                
                                break;
                }
        }
        
        private function getForm($stap)
        {
                $form = new form($this->post);
                switch($stap)
                {
                        case 1:
                                
                                break;
                }
                return $form;
        }
        
        private function register($data)
        {
                
        }
}

?>
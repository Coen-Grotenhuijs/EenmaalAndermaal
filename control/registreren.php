<?php

class registrerenControl extends control
{
	public function run()
	{
                $this->replaceView('title','Registreren');
                
                if($this->logged_in)
                {
                        header('Location: home.php');
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

                if($_SESSION['registratiestap']==1)
                {
                        $vragen = $this->registrerenModel->getVragen();
                        foreach($vragen as $key=>$value)
                        {
                                $this->loadView('registreren/vraag','next_vraag');
                                $this->replaceView('tekstvraag', $value['Tekstvraag']);
                                $this->replaceView('vraag_nummer', $value['Vraagnummer']);
                        }
                        $this->replaceView('next_vraag', '');
                        
                }
                
                if($_SESSION['registratiestap']==4)
                {
                        $this->register($_SESSION);
                }
                
                if(!empty($this->post['next']))
                {
                        $form = $this->getForm($_SESSION['registratiestap']);
                        
                        if($form->valid())
                        {
                                if($_SESSION['registratiestap']<4) $_SESSION['registratiestap']++;
                                header('Location: registreren.php');
                        }
                        $this->view->replace_array($form->geterrors());
                        $this->view->replace_array($form->getclasses());
                }
                elseif(!empty($this->post['previous']))
                {
                        if($_SESSION['registratiestap']>1)
                        {
                                $_SESSION['registratiestap']--;
                                header('Location: registreren.php');
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
                                $_SESSION['reg_tussenvoegsel'] = $this->post['tussenvoegsel'];
                                $_SESSION['reg_achternaam'] = $this->post['achternaam'];
                                $_SESSION['reg_straat'] = $this->post['straat'];
                                $_SESSION['reg_huisnummer'] = $this->post['huisnummer'];
                                $_SESSION['reg_woonplaats'] = $this->post['woonplaats'];
                                $_SESSION['reg_postcode'] = $this->post['postcode'];
                                $_SESSION['reg_land'] = $this->post['land'];
                                $_SESSION['reg_geboortedatum'] = $this->post['geboortedatum'];
                                $_SESSION['reg_tel'] = $this->post['tel'];
                                
                                break;
                }
        }
        
        private function getForm($stap)
        {
                $form = new form($this->post);
                switch($stap)
                {
                        case 1:
                                $user = $this->registrerenModel->getUserExists($this->post['gebruikersnaam']);
                                $email = $this->registrerenModel->getUserEmail($this->post['email']);
                                $form->check('email', array('not null'=>true,'length'=>'0-35','isemail'=>true, 'null'=>array($email, 'Dit e-mailadres is reeds bezet.')));
                                $form->check('gebruikersnaam', array('not null'=>true, 'length'=>'0-30', 'null'=>array($user,'Deze gebruikersnaam is reeds bezet.')));
                                $form->check('wachtwoord', array('not null'=>true, 'length'=>'8-15', 'equals'=>$this->post['herh_wachtwoord']));
                                $form->check('herh_wachtwoord', array('not null'=>true));
                                $form->check('vraag', array('not null'=>true, 'length'=>'0-10'));
                                $form->check('wachtwoord', array('not null'=>true, 'length'=>'0-15'));
                                break;
                        case 2:
                                $form->check('voornaam',array('not null'=>true, 'length'=>'0-30'));
                                $form->check('tussenvoegsel',array('not null'=>false, 'length'=>'0-30'));
                                $form->check('achternaam',array('not null'=>true, 'length'=>'0-30'));
                                $form->check('straat',array('not null'=>true, 'length'=>'0-30'));
                                $form->check('huisnummer',array('not null'=>true));
                                $form->check('woonplaats',array('not null'=>true));
                                $form->check('postcode',array('not null'=>true));
                                $form->check('land',array('not null'=>true));
                                $form->check('geboortedatum',array('not null'=>true));
                                $form->check('tel',array('not null'=>true));
                                break;
                }
                return $form;
        }
        
        private function register($data)
        {
                $code = $this->registrerenModel->register($data);
                unset($_SESSION['registratiestap']);
                
                mail($data['reg_email'], "Uw registratie bij EenmaalAndermaal!", "Welkom bij EenmaalAndermaal!\n Klik op de volgende link om uw account te activeren: \n <a href='http://iproject31.icasites.nl/activeren.php?gebruiker=".$data['reg_gebruikersnaam']."&code=".$code."'>Activeer uw account</a>");
        }
}

?>
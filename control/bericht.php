<?php

class berichtControl extends control
{
	public function run()
	{
                $gebruiker = $this->berichtModel->getVerkoper($this->get['gebruiker']);
                
                if(empty($gebruiker))
                {
                        header('Location: home.php');
                }
                
                if(!$this->logged_in)
                {
                        header('Location: home.php');
                }
                
                $this->replaceView('title','Bericht versturen naar '.$gebruiker['Gebruikersnaam']);
                
                if(!empty($this->post['submit']) && !empty($this->post['bericht']))
                {
                        $mail = mail($gebruiker['Emailadres'], 'Bericht op EenmaalAndermaal van '.$this->berichtModel->getCurrentUser(), "De gebruiker ".$this->berichtModel->getCurrentUser()." heeft u het volgende bericht gestuurd: \n".strip_tags($this->post['bericht'])."\n\n Klik op de volgende link om op dit bericht te reageren: <a href='http://iproject31.icasites.nl/bericht.php?gebruiker=".$this->berichtModel->getCurrentUser()."'>Reageren op dit bericht</a>");
                        if($mail)
                        {
                                $this->loadView('bericht/succes', 'content');
                        }
                        else {
                                $this->loadView('bericht/fout', 'content');
                        }
                        
                }
                else
                {
                        $this->loadView('bericht/bericht', 'content');
                }
	}
        
}

?>
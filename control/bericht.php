<?php

class berichtControl extends control
{
	public function run()
	{
                $gebruiker = $this->berichtModel->getVerkoper($this->get['gebruiker']);
                
                if(empty($gebruiker))
                {
                        header('Location: home');
                }
                
                if(!$this->logged_in)
                {
                        header('Location: home');
                }
                
                $this->replaceView('title','Bericht versturen naar '.$gebruiker['Gebruikersnaam']);
                
                if(!empty($this->post['submit']) && !empty($this->post['bericht']))
                {
                        $mail = true;
                        // mail functie
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
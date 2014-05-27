<?php

class wachtwoordvergetenControl extends control
{
	public function run()
	{
		$this->loadView('home','content');
                
                $gebruiker = $this->wachtwoordvergetenModel->getUser($this->get['username']);
                
                if(empty($gebruiker))
                {
                        header('Location: home');
                }
                
                if(!empty($this->post['submit']))
                {
                        if(strtolower($this->post['antwoord']) == strtolower($gebruiker['Antwoordtekst']))
                        {
                                
                        }
                }
                else
                {
                        $vraag = $this->wachtwoordvergetenModel->getVraag($gebruiker['Vraag']);
                }
	}
}

?>
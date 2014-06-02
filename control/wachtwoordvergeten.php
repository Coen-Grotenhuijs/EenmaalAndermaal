<?php

class wachtwoordvergetenControl extends control
{
	public function run()
	{
                $gebruiker = $this->wachtwoordvergetenModel->getUser($this->get['username']);
                
                if(empty($gebruiker))
                {
                        header('Location: home');
                }
                
                if(!empty($this->post['submit']))
                {
                        if(strtolower($this->post['antwoord']) == strtolower($gebruiker['Antwoordtekst']))
                        {
                                $seed = $this->getSeed();
                                header('Location: wachtwoordvergeten?key='.$this->getKey($gebruiker, $seed).'&seed='.$seed.'&username'.$this->get['username']);
                        }
                }
                else
                {
                        if(!empty($this->get['key']) && !empty($this->get['seed']) && !empty($this->get['username']))
                        {
                                if($this->get['key']==$this->getKey($gebruiker, $this->get['seed']))
                                {
                                        if(!empty($this->post['submit_pass']))
                                        {
                                                
                                        }
                                        else
                                        {
                                                
                                        }
                                }
                                else
                                {
                                        header('Location: wachtwoordvergeten?username='.$this->get['username']);
                                }
                        }
                        else if(!empty($this->get['mail']))
                        {
                                $seed = $this->getSeed();
                                mail($gebruiker['Emailadres'], 'Herstellen account Eenmaalandermaal', "Klik op de volgende link om je account te herstellen:<br> <a href='wachtwoordvergeten?key=".$this->getKey($gebruiker, $seed)."&seed=".$seed."'>LINK</a>");
                                echo "Klik op de volgende link om je account te herstellen:<br> <a href='wachtwoordvergeten?key=".$this->getKey($gebruiker, $seed)."&seed=".$seed."&username=".$this->get['username']."'>LINK</a>";
                        }
                        else
                        {
                                $seed = $this->getSeed();
                                $this->loadView('wachtwoordvergeten/wachtwoordvergeten', 'content');
                                $vraag = $this->wachtwoordvergetenModel->getVraag($gebruiker['Vraag']);
                                echo $this->getKey($gebruiker, $seed);
                        }
                }
	}
        
        private function getKey($gebruiker, $seed)
        {
                return sha1($gebruiker['Voornaam'].$gebruiker['Gebruikersnaam'].date('d').'bloempot'.$seed);
        }
        
        private function getSeed()
        {
                return substr(microtime(false), 2,5);
        }
}

?>
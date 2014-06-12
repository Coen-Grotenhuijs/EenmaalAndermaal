<?php

class wachtwoordvergetenControl extends control
{
	public function run()
	{
                $this->replaceView('title','Wachtwoord vergeten');
                
                $gebruiker = $this->wachtwoordvergetenModel->getUser($this->get['username']);
                
                if(empty($gebruiker))
                {
                        $this->loadView('wachtwoordvergeten/gebruikersnaam', 'content');
                }
                else
                {
                        if(!empty($this->post['submit']))
                        {
                                if(strtolower($this->post['antwoord']) == strtolower($gebruiker['Antwoordtekst']))
                                {
                                        $seed = $this->getSeed();
                                        $this->loadView('wachtwoordvergeten/code', 'content');
                                        $this->replaceView('url', 'wachtwoordvergeten.php?key='.$this->getKey($gebruiker, $seed).'&seed='.$seed.'&username'.$this->get['username']);
                                }
                                else
                                {
                                        $this->loadView('wachtwoordvergeten/foutantwoord', 'content');
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
                                                header('Location: wachtwoordvergeten.php?username='.$this->get['username']);
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
                                        
                                        $this->replaceView('vraag', $vraag);
                                }
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
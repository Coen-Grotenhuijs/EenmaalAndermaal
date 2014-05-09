<?php

class registrerenControl extends control
{
	public function run()
	{
		$this->loadView('registreren/registreren','content');
                
                if($this->logged_in)
                {
                        header('Location: home');
                }
                        
                if(empty($_SESSION['registratiestap'])) $_SESSION['registratiestap'] = 1;
                
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
                        }
                }
	}
        
        private function saveData($stap)
        {
                switch($stap)
                {
                        case 1:
                                
                                break;
                }
        }
        
        private function getForm($stap)
        {
                
                switch($stap)
                {
                        case 1:
                                
                                break;
                }
                
        }
        
        private function register($data)
        {
                
        }
}

?>
<?php

class activerenControl extends control
{
	public function run()
	{
                $gebruiker = $this->activerenModel->getUser($this->get['gebruiker']);
                
                if($this->logged_in)
                {
                        header('Location: home');
                }
                
                $this->replaceView('title','Account activeren');
                
                if(!empty($gebruiker) && !empty($this->get['code']))
                {
                        $succes = $this->activerenModel->getUserCode($this->get['gebruiker'], $this->get['code']);
                        if($succes)
                        {
                                $this->activerenModel->activeer($this->get['gebruiker']);
                                $this->loadView('activeren/succes', 'content');
                        }
                        else {
                                $this->loadView('activeren/fout', 'content');
                        }
                        
                }
                else
                {
                        $this->loadView('activeren/activeren', 'content');
                }
	}
        
}

?>
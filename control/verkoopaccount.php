<?php

class feedbackControl extends control
{
	public function run()
	{
                if(!$this->logged_in)
                {
                        header('Location: home.php');
                }
                $this->replaceView('title', 'Verkoopaccount aanvragen');
                if(empty($this->get['tab']))
                {
                        if(!empty($this->post['submit']))
                        {
                                
                        }
                        else
                        {
                                $this->loadView('verkoopaccount/aanvragen','content');
                        }
                        
                }
                else
                {
                        if(!empty($this->post['submit']))
                        {
                                $code = $this->getCode($this->user, $this->post['code'], $this->post['seed']);
                                if($ok)
                                {
                        		$this->loadView('verkoopaccount/bevestigen/succes','content');
                                }
                                else
                                {
                        		$this->loadView('verkoopaccount/bevestigen/fout','content');
                                }
                        }
                        else
                        {
                		$this->loadView('verkoopaccount/bevestigen/bevestigen','content');
                        }
                }
	}
}

?>
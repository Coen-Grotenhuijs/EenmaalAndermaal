<?php

class veilingControl extends control
{
	public function run()
	{
                $this->loadModel('relevantie');
                
                $veiling = $this->veilingModel->getVeiling(intval($this->get['id']));
                
                if(empty($veiling))
                {
                        header('Location: zoek');
                }
                
                if($this->logged_in) $this->relevantieModel->addView($this->get['id']);
                
                if(!empty($this->post['submitbod']) && $this->logged_in && strtolower($this->user)!=strtolower($this->veilingModel->getUserLastBid($this->get['id'])) && strtolower($this->user)!=strtolower($this->veilingModel->getOwner($this->get['id'])))
                {
                        $min = $this->veilingModel->getMinBod($this->get['id']);
                        
                        $this->post['bod'] = str_replace(',','.',$this->post['bod']);
                        
                        $form = new form($this->post);
                        $form->check('bod',array('length'=>'0-13', 'isnumber'=>true, 'bigger'=>array($min, 'Dit bod is een te laag bod, het minimumbod is '.$min.'.')));
                        
                        if($form->valid())
                        {
                                $this->veilingModel->addBod($this->get['id'], $this->post['bod'], $this->user);
//                                $this->relevantieModel->addBid($this->get['id']);
                        }
                }
                $breadcrumb = $this->veilingModel->getBreadcrumb(intval($this->get['id']));
                $this->loadView('veiling/breadcrumbs','title');
                $this->replaceView('title', $veiling['Titel']);
                $this->replaceView('breadcrumbs',$breadcrumb);
                
		$this->loadView('veiling/veiling','content');
                
                if(empty($veiling['Filenaam']))
                {
                        $veiling['Filenaam'] = 'uploads/empty.jpg';
                }
                else
                {
                        $veiling['Filenaam'] = 'uploads/empty.jpg';
                }

                foreach($veiling as $key=>$value)
                {
                        $this->replaceView('veiling_'.$key, $value);
                }

                $timer = new Timer();
                $time = $timer->getTimestamp($veiling['Looptijdeindedag'], $veiling['Looptijdeindetijdstip']);
                $this->replaceView('timer_class',$timer->setTimer($time));

                
                $biedingen = $this->veilingModel->getBiedingen(intval($this->get['id']));
                foreach($biedingen as $key=>$value)
                {
                        $this->loadView('veiling/bod','next_bod');
                        $this->replaceView('bod_bodbedrag', $value['Bodbedrag']);
                        $this->replaceView('bod_gebruiker', $value['Gebruiker']);
                }
                if(empty($biedingen))
                {
                        $this->loadView('veiling/geenbiedingen','next_bod');
                }
                $this->replaceView('next_bod','');
                
                if($this->logged_in)
                {
                        if(strtolower($this->user)==strtolower($this->veilingModel->getOwner($this->get['id'])))
                        {
                                $this->loadView('veiling/eigenaar', 'veiling_bieden');
                        }
                        if(strtolower($this->user)==strtolower($this->veilingModel->getUserLastBid($this->get['id'])))
                        {
                                $this->loadView('veiling/algeboden', 'veiling_bieden');
                        }
                        if(strtolower($this->user)!=strtolower($this->veilingModel->getUserLastBid($this->get['id'])) && strtolower($this->user)==strtolower($this->veilingModel->getOwner($this->get['id'])))
                        {
                                $this->loadView('veiling/loggedin', 'veiling_bieden');
                        }
                }
                else
                {
                        $this->loadView('veiling/loggedout', 'veiling_bieden');
                }
                if(!empty($form))
                {
                        $this->view->replace_array($form->geterrors());
                        $this->view->replace_array($form->getclasses());
                }
	}
}

?>
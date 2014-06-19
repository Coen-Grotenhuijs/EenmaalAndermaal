<?php

class veilingControl extends control
{
	public function run()
	{
                $this->loadModel('relevantie');
                
                $veiling = $this->veilingModel->getVeiling(round($this->get['id']));
                
                
                if(empty($veiling))
                {
                        header('Location: zoek.php');
                }
                
                if($this->logged_in) $this->relevantieModel->addView($this->get['id']);
                
                if(!empty($this->post['submitbod']) && $this->logged_in && strtolower($this->user)!=strtolower($this->veilingModel->getUserLastBid($this->get['id'])) && strtolower($this->user)!=strtolower($this->veilingModel->getOwner($this->get['id'])))
                {
                        $min = $this->veilingModel->getMinBod(round($this->get['id']));
                        
                        // Minimum bod bepalen aan de hand van ophogingsregels
                        $add = 0;
                        
                        $settings = new settings();
                        $values = $settings->getPart('opbod');
                        foreach($values as $key=>$value)
                        {
                                $temp = explode("-",$key);
                                if(empty($temp[0]) && !empty($temp[1]) && $min<$temp[1])
                                {
                                        $add = $value;
                                }
                                elseif(!empty($temp[0]) && empty($temp[1]) && $min>$temp[0])
                                {
                                        $add = $value;
                                }
                                elseif($min>$temp[0] && $min<$temp[1])
                                {
                                        $add = $value;
                                }
                        }
                        
                        $min += $add;
                        
                        $this->post['bod'] = str_replace(',','.',$this->post['bod']);
                        
                        $form = new form($this->post);
                        $form->check('bod',array('length'=>'0-13', 'isnumber'=>true, 'bigger'=>array($min, 'Dit bod is een te laag bod, het minimumbod is '.$min.'.')));
                        
                        if($form->valid())
                        {
                                $this->veilingModel->addBod($this->get['id'], $this->post['bod'], $this->user);
//                                $this->relevantieModel->addBid($this->get['id']);
                        }
                }
                $breadcrumb = $this->veilingModel->getBreadcrumb(round($this->get['id']));
                $this->loadView('veiling/breadcrumbs','title');
                $this->replaceView('title', strip_tags($veiling['Titel']));
                $this->replaceView('breadcrumbs',$breadcrumb);
                
		$this->loadView('veiling/veiling','content');

                $images = $this->veilingModel->getImages(round($this->get['id']));

                if(!empty($images))
                {
                        $this->replaceView('veiling_filenaam', $images[0]['Filenaam']);
                }
                else
                {
                        $this->replaceView('veiling_filenaam', 'empty.jpg');
                }
                
                array_shift($images);
                
                foreach($images as $key=>$value)
                {
                        $this->loadView('veiling/afbeelding', 'next_afbeelding');
                        $this->replaceView('veiling_filenaam', $value['Filenaam']);
                }
                
                $this->replaceView('next_afbeelding','');
                        

                foreach($veiling as $key=>$value)
                {
                        $this->replaceView('veiling_'.$key, strip_tags($value));
                }

                $timer = new Timer();
                $time = $timer->getTimestamp($veiling['Looptijdeindedag'], $veiling['Looptijdeindetijdstip']);
                $this->replaceView('timer_class',$timer->setTimer($time));

                
                $biedingen = $this->veilingModel->getBiedingen(round($this->get['id']));
                foreach($biedingen as $key=>$value)
                {
                        $temp = explode("/",$value['Boddag']);
                        if($temp[0]==date('d') && $temp[1]==date('n') && $temp[2]==date('Y')) $datum = 'vandaag';
                        elseif($temp[0]==date('d', time()-3600*24) && $temp[1]==date('n', time()-3600*34) && $temp[2]==date('Y', time()-3600*24)) $datum = 'gisteren';
                        else $datum = $temp[0].'/'.$temp[1];
                        $this->loadView('veiling/bod','next_bod');
                        $this->replaceView('bod_bodbedrag', $value['Bodbedrag']);
                        $this->replaceView('bod_gebruiker', $value['Gebruiker']);
                        $this->replaceView('bod_tijdstip', $datum." ".$value['Bodtijdstip']);
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
                        if(strtolower($this->user)!=strtolower($this->veilingModel->getUserLastBid($this->get['id'])) || strtolower($this->user)==strtolower($this->veilingModel->getOwner($this->get['id'])))
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
<?php

class mijnaccountControl extends control
{
	public function run()
	{
		$this->loadView('mijnaccount/mijnaccount','content');
                
                $this->replaceView('title','Gegevens aanpassen');
                
                if($this->logged_in == false)
                {
                        header('Location: home.php');
                }
                
                if(!empty($this->post['submit_account']))
                {
                        $form = new form($this->post);
                        $form->check('voornaam',array('not null'=>true, 'length'=>'0-30'));
                        $form->check('tussenvoegsel',array('not null'=>false, 'length'=>'0-30'));
                        $form->check('achternaam',array('not null'=>true, 'length'=>'0-30'));
                        $form->check('adresregel1',array('not null'=>true, 'length'=>'0-30'));
                        $form->check('adresregel2',array('not null'=>true));
                        $form->check('plaatsnaam',array('not null'=>true));
                        $form->check('postcode',array('not null'=>true));
                        $form->check('land',array('not null'=>true));
                        $form->check('geboortedag',array('not null'=>true));
                        $form->check('tel1',array('not null'=>true));
                        
                        if($form->valid())
                        {
                                $this->mijnaccountModel->update($this->post);
                        }
                        
                        $this->view->replace_array($form->geterrors());
                        $this->view->replace_array($form->getclasses());
                }
                
                if(!empty($this->post['submit_password']))
                {
                        $password = $this->mijnaccountModel->getPass();
                        
                        $equal = $this->post['wachtwoord']==$this->post['herhaal_wachtwoord'];
                        
                        $form = new form($this->post);
                        $form->check('huidig_wachtwoord', array('not null'=>true, 'length'=>'0-30', 'equals'=>array($password, 'Het wachtwoord is onjuist.')));
                        $form->check('wachtwoord', array('not null'=>true, 'length'=>'8-30', 'not empty'=>array($equal, 'De wachtwoorden komen niet overeen.')));
                        $form->check('herhaal_wachtwoord', array('not null'=>true, 'length'=>'8-30'));
                        
                        if($form->valid())
                        {
                                $this->mijnaccountModel->updatePass($this->post);
                        }

                        $this->view->replace_array($form->geterrors());
                        $this->view->replace_array($form->getclasses());
                }
                

                $gebruiker = $this->mijnaccountModel->getProfielGegevens();
                
                $data = array();

                foreach($gebruiker as $key=>$value)
                {
                        if(isset($this->post[strtolower($key)]))
                        {
                                $data['gebruiker_'.$key] = $this->post[strtolower($key)];
                        }
                        else
                        {
                                $data['gebruiker_'.$key] = $value;
                        }
                }
                
                $this->view->replace_array($data);
                
                $this->view->cleanup('GEBRUIKER');
	}
}

?>
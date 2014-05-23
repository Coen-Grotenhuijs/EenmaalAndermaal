<?php

class veilingControl extends control
{
	public function run()
	{
                $veiling = $this->veilingModel->getVeiling(intval($this->get['id']));
                
                if(empty($veiling))
                {
                        header('Location: zoek');
                }
                
                if(!empty($this->post['submitbod']))
                {
                        $form = new form($this->post);
                        $form->check('bod',array('length'=>'0-4', 'isnumber'=>true));
                        
                        if($form->valid())
                        {
                                
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
                        $this->loadView('veiling/loggedin', 'veiling_bieden');
                }
                else
                {
                        $this->loadView('veiling/loggedout', 'veiling_bieden');
                }
	}
}

?>
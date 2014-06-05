<?php

class nieuweveilingControl extends control
{
        private $fileError;
        
	public function run()
	{
                $veiling = array();

                if(!empty($this->get['veiling']))
                {
                        $veiling = $this->nieuweveilingModel->getVeiling($this->get['veiling']);
                }
                
                
                if(!empty($veiling))
                {
                        
                }
                elseif(!$this->logged_in)
                {
                        
                }
                else
                {
                        $this->loadView('nieuweveiling/nieuweveiling', 'content');
                        $this->replaceView('title','Veiling aanmaken');
                        
                        if(!empty($this->post['submit']))
                        {
                                $this->post['verzendkosten'] = str_replace(',','.',$this->post['verzendkosten']);
                                $this->post['startprijs'] = str_replace(',','.',$this->post['startprijs']);
                                
                                
                                $form = new form($this->post);
                                
                                $form->check('voorwerpnaam', array('not null'=>true, 'length'=>'0-100'));
                                $form->check('beschrijving', array('not null'=>true, 'length'=>'0-1000'));
                                $form->check('plaatsnaam', array('not null'=>true, 'length'=>'0-100'));
                                $form->check('land', array('not null'=>true, 'length'=>'0-100'));
                                $form->check('looptijd', array('not null'=>true, 'length'=>'0-5'));
                                $form->check('verzendinstructies', array('not null'=>true, 'length'=>'0-27'));
                                $form->check('verzendkosten', array('not null'=>true, 'length'=>'0-10', 'isnumber'=>true));
                                $form->check('startprijs', array('not null'=>true, 'length'=>'0-10', 'isnumber'=>true));
                                $form->check('betalingswijze', array('not null'=>true, 'length'=>'0-9'));
                                $form->check('betalingsinstructies', array('not null'=>true, 'length'=>'0-23'));
                                
                                $files = array();
                                if(!empty($_FILES))
                                {
                                        $files = $this->uploadFiles();
                                }
                                
                                print_r($this->fileError);
                                
                                if($form->valid() && empty($this->fileError))
                                {
                                        print_r($files);
                                        $id = $this->nieuweveilingModel->addVeiling($this->post);
                                        $this->nieuweveilingModel->addBestanden($files, $id);
                                        echo $id;
                                }
                                
                                $this->view->replace_array($form->geterrors());
                                $this->view->replace_array($form->getclasses());
                                
                        }
                }

        }
        
        public function uploadFiles()
        {
                $files = array();
                foreach($_FILES['file']['tmp_name'] as $key=>$value)
                {
                        if(empty($value)) continue;
                        
                        if($this->fileValid($key))
                        {
                                $path = getcwd().'\img\uploads\\';
                                $name = $this->generateRandomString(20);

                                $temp = $_FILES['file']['name'][$key];
                                $ext = ".".pathinfo($temp, PATHINFO_EXTENSION);

                                move_uploaded_file($value, $path.$name.$ext);
                                $files[] = $name.$ext;
                        }
                }
                
                return $files;
        }
        
        
        public function generateRandomString($length) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }
                return $randomString;
        }
        
        public function fileValid($file)
        {
                $allowed = array('.jpg','.bmp','.png','.JPG','.jpeg','.JPEG');
                
                $name = $_FILES['file']['name'][$file];
                
                $temp = $_FILES['file']['name'][$file];
                $ext = ".".pathinfo($temp, PATHINFO_EXTENSION);
                
                list($x, $y) = getimagesize($_FILES['file']['tmp_name'][$file]);
                
                $valid = true;
                if(!in_array($ext, $allowed))
                {
                        $valid = false;
                        $this->fileError[] = 'Het bestand '.$name.' heeft een ongeldige extensie (jpg, bmp, png toegestaan).';
                }
                elseif($_FILES['file']['size'][$file]>1024*1024)
                {
                        $valid = false;
                        $this->fileError[] = 'Het bestand '.$name.' is groter dan 1MB.';
                }
                elseif($x>1000 || $y>1000)
                {
                        $valid = false;
                        $this->fileError[] = 'Het bestand '.$name.' is groter dan de maximaal toegestane resolutie (1000 bij 1000 pixels).';
                }
                return $valid;
        }
}

?>
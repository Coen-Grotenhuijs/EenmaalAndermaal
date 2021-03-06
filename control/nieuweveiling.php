<?php

class nieuweveilingControl extends control
{
        private $fileError;
        
	public function run()
	{
                $this->replaceView('title','Veiling aanmaken');
                $veiling = array();

                if(!empty($this->get['veiling']))
                {
                        $veiling = $this->nieuweveilingModel->getVeiling($this->get['veiling']);
                }
                
                $verkoper = $this->nieuweveilingModel->getVerkoper();
                if(!empty($veiling))
                {
                        $this->loadView('nieuweveiling/aangemaakt', 'content');
                        $this->replaceView('id',$this->get['veiling']);
                }
                elseif(!$this->logged_in)
                {
                        $this->loadView('nieuweveiling/login', 'content');
                }
                elseif(empty($verkoper))
                {
                        $this->loadView('nieuweveiling/geenverkoper', 'content');
                }
                else
                {
                        $this->loadView('nieuweveiling/nieuweveiling', 'content');
                        
                        $rubrieken = $this->rubriekenModel->getRubrieken();

                        foreach($rubrieken as $key=>$value)
                        {
                                $this->loadView('header/rubriek','next_rubriek_dropdown');
                                $this->replaceView('rubriek_naam', $value['Naam']);
                                $this->replaceView('rubriek_nummer', $value['Nummer']);
                        }
                        $this->replaceView('next_rubriek_dropdown', '');
                        
                        if(!empty($this->post['submit']))
                        {
                                $this->post['verzendkosten'] = str_replace(',','.',$this->post['verzendkosten']);
                                $this->post['startprijs'] = str_replace(',','.',$this->post['startprijs']);
                                
                                $rubriek = $this->nieuweveilingModel->getRubriek($this->post['rubriek']);
                                
                                $form = new form($this->post);
                                
                                $form->check('voorwerpnaam', array('not null'=>true, 'length'=>'0-100'));
                                $form->check('beschrijving', array('not null'=>true, 'length'=>'0-1000'));
                                $form->check('plaatsnaam', array('not null'=>true, 'length'=>'0-100'));
                                $form->check('land', array('not null'=>true, 'length'=>'0-100'));
                                $form->check('verzendkosten', array('not null'=>true, 'length'=>'0-10', 'isnumber'=>true));
                                $form->check('startprijs', array('not null'=>true, 'length'=>'0-10', 'isnumber'=>true));
                                $form->check('betalingswijze', array('not null'=>true, 'length'=>'0-9'));
                                $form->check('betalingsinstructies', array('not null'=>true, 'length'=>'0-23'));
                                $form->check('rubriek', array('true'=>$rubriek));
                                
                                $files = array();
                                if(!empty($_FILES))
                                {
                                        $files = $this->uploadFiles();
                                }
                                
                                
                                if($form->valid() && empty($this->fileError))
                                {
                                        $id = $this->nieuweveilingModel->addVeiling($this->post);
                                        $this->nieuweveilingModel->addBestanden($files, $id);
                                        header('Location: nieuweveiling.php?veiling='.$id);
                                }
                                
                                $this->view->replace_array($form->geterrors());
                                $this->view->replace_array($form->getclasses());
                                
                        }
                        $this->replaceView('file_errors', implode('<br>', $this->fileError));
                }

        }
        
        public function uploadFiles()
        {
                $files = array();
                foreach($_FILES['file']['tmp_name'] as $key=>$value)
                {
                        if(empty($value)) continue;
                        
                        if($this->fileValid($key) && count($files)<4)
                        {
                                $path = getcwd().'\upload\\';
                                $name = $key.$this->generateRandomString(20);

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
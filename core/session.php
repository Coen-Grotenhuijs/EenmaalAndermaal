<?php

class session
{
    public $logged_in = false;
    
    public $user = "";
	public $loginform;
	
    public function __construct()
    {
        if(isset($_SESSION['user']) && isset($_SESSION['pass']))
        {
			if($this->sessionModel->validateSession($_SESSION))
			{
				$this->logged_in = true;
				$this->user = $_SESSION['user'];
			}
        }
		
		if(isset($_POST['submitlogin']))
		{
			$form = new form($this->post);
			
			$user = $this->sessionModel->getUser($this->post['username']);
			$pass = $this->sessionModel->getUserPass($this->post['username'], $this->post['password']);
			
			$form->check('username',	array(	'not null'=>'true',
												'not empty'=>array($user,'Gebruikersnaam bestaat niet.')));
			$form->check('password', 	array( 	'not null'=>'true',
												'not empty'=>array($pass,'Wachtwoord komt niet overeen.')));
			$this->loginform = $form;
			if($form->valid())
			{
				$_SESSION['user'] = $this->post['username'];
				$_SESSION['pass'] = $this->post['password'];
				header('Location: '.$_SERVER['REQUEST_URI']);
			}
		}
		if(empty($_SESSION['winkelwagen'])) $_SESSION['winkelwagen'] = array();
    }
	
	public function loginAfterRegister($user, $pass)
	{
		$_SESSION['user'] = $user;
		$_SESSION['pass'] = $pass;
	}
}

?>
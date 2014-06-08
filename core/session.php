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
			if($this->sessionModel->validateSession($_SESSION) && $this->sessionModel->getBlocked($_SESSION['user'])==0)
			{
				$this->logged_in = true;
				$this->user = $_SESSION['user'];
			}
                        if($this->sessionModel->getBlocked($_SESSION['user'])!=0)
                        {
                                unset($_SESSION);
                        }
        }
		
		if(isset($_POST['submitlogin']))
		{
			$form = new form($this->post);
			
			$user = $this->sessionModel->getUser($this->post['username']);
			$pass = $this->sessionModel->getUserPass($this->post['username'], $this->post['password']);
                        
                        $blocked = $this->sessionModel->getBlocked($this->post['username']);

                        $activated = $this->sessionModel->getActivated($this->post['username']);
                        
			$form->check('username',	array(	'not null'=>'true',
								'not empty'=>array($user,'Gebruikersnaam bestaat niet.'),
                                                                'null'=>array($blocked, 'Dit account is inactief.',
                                                                'not null'=>array($activated, 'Dit account is nog niet geactiveerd.'))));
			$form->check('password', 	array( 	'not null'=>'true',
												'not empty'=>array($pass,'Wachtwoord komt niet overeen.')));
			$this->loginform = $form;
			if($form->valid())
			{
				$_SESSION['user'] = $user['Gebruikersnaam'];
				$_SESSION['pass'] = $user['Wachtwoord'];
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
<?php

class loguitControl extends control
{
	public function run()
	{
		unset($_SESSION['user']);
		unset($_SESSION['pass']);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
}

?>
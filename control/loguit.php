<?php

class loguitControl extends control
{
	public function run()
	{
		unset($_SESSION);
                session_destroy();
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
}

?>
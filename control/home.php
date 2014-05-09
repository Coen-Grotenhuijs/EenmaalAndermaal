<?php

class homeControl extends control
{
	public function run()
	{
		$this->loadView('home','content');
	}
}

?>
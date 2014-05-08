<?php

class notfoundControl extends control
{
	public function run()
	{
		$this->loadView('notfound','content');
	}
}

?>
<?php

class dashboardControl extends control
{
	public function run()
	{
		$this->loadView('dashboard','content');
                $this->replaceView('BIEDINGEN', 12345);
                $this->replaceView('title','Dashboard');
	}
}

?>
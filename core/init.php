<?php
// initialiseren
$start = microtime(TRUE);
session_start();
ini_set('session.gc_maxlifetime',7200);

// core bestanden laden
include('core/database.php');
include('core/view.php');
include('core/model.php');
include('core/control.php');
include('core/settings.php');


// libs laden
$settings = new settings();
$libs = $settings->getPart('libraries');
foreach($libs as $key=>$value)
{
        if(!empty($key))
        {
                include('libs/'.trim($key).'.php');
        }
}

// url bepalen
$urltemp = explode('/',$_SERVER["REQUEST_URI"]);
$urltemp2 = explode('.',$urltemp[count($urltemp)-1]);
$url = $urltemp2[0];

if(empty($url)) $url = 'home';

// control openen
if(file_exists('control/'.$url.'.php'))
{
	include('control/'.$url.'.php');
	if(class_exists($url."Control"))
	{
		$classname = $url."Control";
		$class = new $classname();
		$class->run();
	}
}
else
{
	require('control/notfound.php');
	$class = new notfoundControl;
	$class->run();
}
unset($class);


?>
<?php

class breadcrumb
{
	private $content = array();

	public function add($tag, $url)
	{
		$this->content[] = array('tag'=>$tag, 'url'=>$url);
	}
	
	public function __toString()
	{
		$return = "";
		foreach($this->content as $key=>$value)
		{
			$return .= ' >> ';
			$return .= '<a href="'.$value['url'].'" class="crumb">'.$value['tag'].'</a>';
		}
		return $return;
	}
}
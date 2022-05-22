<?php

class LCsvCache {

	private $dir;
	private $refresh;

	public function __construct() {
		global $CONFIG;

		$this->dir = $CONFIG['cache']['dir'];
		$this->refresh = ($CONFIG['cache']['refresh'] ? $CONFIG['cache']['refresh'] : 1)*60;
	}

	private function parseAction($action)
	{
		$searach=array('?',"/",'query','-');
		return str_replace($searach,"",$action);
	}

	public function write($action, $data)
	{
		global $CONFIG;

		$action= $this->parseAction($action);

		$files = scandir($this->dir);

		if($files)foreach($files as $file)
		{
			if(strpos($file,$action)!==false)
				unlink($this->dir.$file);
		}
		$name=$action.time();
		file_put_contents($this->dir.$name, $data);
	}

	public function read($file)
	{
		return json_decode(file_get_contents($this->dir.$file),1);
	}

	public function search($action)
	{
		$action= $this->parseAction($action);

		$files = scandir($this->dir);

		if($files)foreach($files as $file)
		{
			if(strpos($file,$action)!==false)
			{
				$date=str_replace($action,'',$file);

				if($date>0 && $date>(time()-$this->refresh))
				{
					return $this->read($file);
				}
			}
		}
	}

	public function clean()
	{
		$files = scandir($this->dir);

		if($files)foreach($files as $file)
		{
    		if($file!='.' && $file!='..')
    		{
        		unlink($this->dir.$file);
    		}
		}
	}
}
?>

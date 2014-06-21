<?php 
class md5{
private $arr_md5=array();
private $arr_control=array();
private $output=array();
private $co=array();


private $dir;
private $oCache;
	public function __Construct($Directory)
	{
	$this->dir = $Directory;
	$this->oCache = new Cache('ctrl');
	
	$this->arr_control = $this->oCache->getCache();

	}


	private function findexts ($filename) 
	{ 
	$filename = strtolower($filename);
	$exts = preg_split("#[/\\.]#", $filename);
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
	} 


	public function ScanDirectory($dir=false)
	{
		$dir = ($dir) ? $dir : $this->dir;
		
		$MyDirectory = opendir($dir) or die('Erreur');



		
		while($Entry = @readdir($MyDirectory))
		{
			if(is_dir($dir . DS . $Entry) && $Entry != '.' && $Entry != '..')
			{
				$this->ScanDirectory($dir . DS . $Entry);
			}
			elseif($Entry != '.' && $Entry != '..')
			{
			
				if (array_key_exists($dir . DS . $Entry, $this->arr_control))
				{
				$this->arr_md5[$dir . DS . $Entry]=md5_file($dir . DS . $Entry);
					if ($this->arr_md5[$dir . DS . $Entry] != $this->arr_control[$dir . DS . $Entry])
					{
					$this->output[$dir . DS . $Entry] = 'modified';
					}
					else
					{
					$this->output[$dir . DS . $Entry] = 'unmodified';
					}
				}
				else
				{
				$this->arr_md5[$dir . DS . $Entry]=md5_file($dir . DS . $Entry);
				$this->output[$dir . DS . $Entry] = 'norefered';
				}
			}
		}
	closedir($MyDirectory);
	return $this->arr_control;
	}


	public function getOutput()
	{
	return $this->output;
	}
	
	public function stor()
	{
	$this->oCache->setCache(array_merge($this->arr_control, $this->arr_md5));
	}
}
?>
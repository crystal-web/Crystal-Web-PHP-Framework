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


	public function ScanDirectory()
	{
	$MyDirectory = opendir($this->dir) or die('Erreur');
		while($Entry = @readdir($MyDirectory))
		{
			if(is_dir($this->dir.'/'.$Entry) && $Entry != '.' && $Entry != '..')
			{
			$this->ScanDirectory($this->dir.'/'.$Entry);
			}
			elseif($Entry != '.' && $Entry != '..' && $this->findexts($Entry)=='php')
			{
			
				if (array_key_exists($this->dir.'/'.$Entry, $this->arr_control))
				{
				$this->arr_md5[$this->dir.'/'.$Entry]=md5_file($this->dir.'/'.$Entry);
					if ($this->arr_md5[$this->dir.'/'.$Entry] != $this->arr_control[$this->dir.'/'.$Entry])
					{
					$this->output[$this->dir.'/'.$Entry] = 'red';
					}
					else
					{
					$this->output[$this->dir.'/'.$Entry] = 'green';
					}
				}
				else
				{
				$this->arr_md5[$this->dir.'/'.$Entry]=md5_file($this->dir.'/'.$Entry);
				$this->output[$this->dir.'/'.$Entry] = 'norefered';
				}
			}
		}
	closedir($MyDirectory);
	
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
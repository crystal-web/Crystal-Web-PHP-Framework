<?php
class Ftp { 
    public $conn;
    public function __construct($url, $port = '21', $timeout = '30')
    {
        $this->conn = ftp_connect($url, $port, $timeout);
        if ($this->conn)
        {
        	Log::setLog('Connection success to ' . $url, 'ftp');
        } else { Log::setLog('Can not connect to ' . $url, 'ftp'); }
    } 
    
    public function __call($func,$a){ 
        if(strstr($func,'ftp_') !== false && function_exists($func)){ 
        	Log::setLog('Call function ' . $func, 'ftp');
            array_unshift($a,$this->conn); 
            return call_user_func_array($func,$a); 
        }else{
        	Log::setLog('Function not exist ' . $func, 'ftp'); 
            // replace with your own error handler. 
            return false;
        } 
    }
    
	/**
	 * 
	 * Test si le isDir est un dossier
	 * @param string $isDir
	 */
	function ftp_isdir($isDir) 
	{
	$res = $this->ftp_size($isDir);
		if($res != "-1")
		{ 
			Log::setLog('Check this is a directory ? ' . $isDir . ' No', 'ftp');
			return false; 
		} 
		else
		{ 
			Log::setLog('Check this is a directory ? ' . $isDir . ' Yes', 'ftp'); 
			return true;
		} 
	}  
    
	
	/**
	 * 
	 * Retourne la liste des dossiers du répértoire courant
	 * @return array
	 */
	public function dir()
	{
		$list = array();
		$dirList = $this->ftp_nlist(".");
		if (count($dirList) && is_array($dirList))
		{
			foreach($dirList AS $k => $v)
			{
				if ($this->ftp_isdir($v))
				{
				$list[] = $v;
				}
			}
		}
		
		return $list;
	}
}

/* Example 
$ftp = new ftp('ftp.example.com'); 
$ftp->ftp_login('username','password'); 
var_dump($ftp->ftp_nlist()); 
*/
?>
<?php
/**
* @title Simple SSH
* @author Christophe BUFFET <developpeur(AROBASE)crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
define('CONSOLE_SSH', '<span style="color:blue">[console] </span>');
define('COMMENT_SSH', '<span style="color:green">[comment] </span>');
class Ssh2 {
private $ver=1.3;
// Identifiant serveur
private $server = 'localhost';		// Serveur
private $port = 22;					// Port

// Identifiant utilisateur
private $login;						// User
private $password;					// Password

private $con = null;				// Flux SSH2
private $log = array();

private $tampon = array();			// Enregistrement de certainne valeur pour économiser les requêtes


	/*********************************************************************************
	*
				Les setter
	*
	*********************************************************************************/
	
	
	/*
	*	Set server to connect
	*/
	public function setServer($host, $port=22)
	{
	$this->server = $host;
	$this->port = $port;
	}	
	
	
	/*
	*	Set user to connect
	*/
	public function setLogin($login, $password)
	{
	$this->login = $login;
	$this->password = $password;
	}

	
	/*********************************************************************************
	*
				Les getter 
	*
	*********************************************************************************/
	

	/*
	*	an eye on the script with log
	*	Return string or array
	*/
	public function getLog($getArray = true)
	{
	$this->log[] = CONSOLE_SSH . "Getting log" . PHP_EOL;
		if ($getArray == false)
		{
		$this->log[] = CONSOLE_SSH . "Log return brut text" . PHP_EOL;
		$buffer=NULL;
			foreach ($this->log as $key => $debug)
			{
			$buffer.=$debug;
			}
		return $buffer;
		}
		else
		{
		$this->log[] = CONSOLE_SSH . "Log return array" . PHP_EOL;
		return $this->log;
		}
	}
	
	
	/*
	*	Auto Connect and auth
	*	It's easy to use, auto verification
	*/
	public function startIt()
	{
		if ($this->connect())
		{
			if ($this->auth())
			{
			return true;
			}
			else
			{
			return false;
			}
		}
		
	return false;
	}
	
	/*
	*	Run connection to the select server
	*/
	public function connect()
	{
	$this->log[] = CONSOLE_SSH . "Welcome to SSH2 Version ".$this->ver." by Christophe BUFFET (developpeur(AROBASE)crystal-web.org" . PHP_EOL;
		// Tentative de connection 
		if( !($this->con = ssh2_connect( $this->server, $this->port /* Default 22 */)) )
		{
			$this->log[] = CONSOLE_SSH . "FAIL: unable to establish connection" . PHP_EOL;
			return false;
		}
		else
		{
		$this->log[] = CONSOLE_SSH . "Okay: Connected to " . $this->server . PHP_EOL;
		return true;
		}
	}
	

	/*
	*	Run authentification to the select server
	*/
	public function auth()
	{
		if( !ssh2_auth_password($this->con, $this->login, $this->password) )
		{
			$this->log[] = CONSOLE_SSH . "FAIL: unable to authenticate" . PHP_EOL;
			return false;
		}	
		else
		{
			$this->log[] = CONSOLE_SSH .  "Okay: logged in... with user " . $this->login . PHP_EOL;
			return true;
		}
	}




	/*********************************************************************************
	*
				Les commandes 
	*
	*********************************************************************************/
	
	
	/*
	*	Run command ;-)
	*/
	public function cmd($cmd, $console=true)
	{
		if (!($stream = ssh2_exec($this->con, $cmd )))
		{
		$this->log[] = CONSOLE_SSH . "FAIL: unable to execute command" . PHP_EOL;
		} else {
		
		$buffer=NULL;
			if ($console == true)
			{
			$this->log[] = CONSOLE_SSH . "EXECUTE : command ".$cmd." : " . PHP_EOL;
			
			// Recuperation des informations recu apres la commande
			stream_set_blocking($stream, true);
			// RaZ Log Console
			$this->tampon['lastLogConsole'] = NULL;
				while ($buf = fread($stream,4096))
				{
				$buffer.=$buf;
				$this->tampon['lastLogConsole'] .= $buf;
				}
			
			$this->log[] = $buffer;
			$this->log[] = CONSOLE_SSH . "END : command ".$cmd." : " . PHP_EOL;
			}

		
		fclose($stream);
		return $buffer;
		}
	}
	

	/*
	*	Search in file
	*/
	public function catSearch($fileName, $rules)
	{
		// if tampon not exist create and stor filename
		if (!isSet($this->tampon['catSearch']['filename']))
		{
		$this->cmd('cat '.$fileName);
				
		$this->tampon['catSearch']['filename']=$fileName;
		$this->tampon['catSearch']['lastlog'] = $this->tampon['lastLogConsole'];
		}
		// IF tampon[val] != filename, is a different file
		elseif($this->tampon['catSearch']['filename']!=$fileName)
		{
		$this->cmd('cat '.$fileName);
		
		$this->tampon['catSearch']['filename']=$fileName;
		$this->tampon['catSearch']['lastlog'] = $this->tampon['lastLogConsole'];
		}
	
	preg_match("#".$rules."#", $this->tampon['catSearch']['lastlog'], $catch);
	$this->log[] = CONSOLE_SSH . "catSearch: " . print_r($catch, true) . PHP_EOL;
	return $catch;
	}
	
	
	/*
	*	Get PID by var name ex: ircd
	*/
	public function getPid($app)
	{
	$this->log[] = CONSOLE_SSH . "Get list of process" . PHP_EOL;
	$this->cmd('ps -x');
	$consoleLog = preg_replace("#\n|\r\n|\r#", '|', $this->getLog(false));
	$array = explode('|', $consoleLog);

		foreach ($array AS $key => $value)
		{
			if (strpos($value, $app))
			{
				// Recherche du PID
				if (preg_match_all('#([0-9]+).*[0-9]{1,2}:[0-9]{1,2}#', $value, $return))
				{
				$this->log[] = CONSOLE_SSH . "PID found " . $return[1][0] . PHP_EOL;
				return (int) $return[1][0];
				}
			}
		}

	$this->log[] = CONSOLE_SSH . "Not found process " . $app . PHP_EOL;
	return false;
	}


	/*
	*	killed in the process type the name or PID
	*/
	public function kill($process)
	{
		if (is_int($process) && $process > 0)
		{
			$this->log[] = CONSOLE_SSH . "kill is int, kill process" . PHP_EOL;
			$this->cmd('kill -9 '.$process);
			return true;
		}
		else
		{
			$this->log[] = CONSOLE_SSH . "kill is var, getting PID" . PHP_EOL;
			$pid = $this->getPid($process);
			
			if (is_int($pid) && $pid > 0)
			{
				CONSOLE_SSH . $this->log[] = "Kill ".$pid . PHP_EOL;
				$this->cmd('kill -9 '.$pid);
				return true;
			}
		
		}
	return false;
	}
	
	/*
	*	Little methode to run a cmd lauch/run app
	*	If you run any app. With $this->cmd()
	*	make, sure you use $this->cmd('./meApp', true)
	*/
	public function run($meapp)
	{
		$this->log[] = CONSOLE_SSH . "Run command ".$meapp . PHP_EOL ;
		$this->cmd($meapp, false);
	}
	
	
	/*********************************************************************************
	*
				Les logs
	*
	*********************************************************************************/
	
	
	/*
	*	for a much cleaner job, commentary used
	*/
	public function addComment($comment)
	{
	$this->log[] = COMMENT_SSH . $comment . PHP_EOL;
	}
	
}

?>
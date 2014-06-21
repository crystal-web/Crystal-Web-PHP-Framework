<?php
/**
* @title Simple SSH
* @author Christophe BUFFET <developpeur(AROBASE)crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @documentation: http://www.crystal-web.org/viki/class-ssh2
*/

define('CONSOLE_SSH', '<span style="color:blue">[console] </span>');
define('COMMENT_SSH', '<span style="color:green">[comment] </span>');
define('ERROR_SSH', '<span style="color:red">[comment] </span>');
class Ssh2 {
private $ver='1.4.2'; 
// Identifiant serveur
private $server = 'localhost';		// Serveur
private $port = 22;					// Port

// Identifiant utilisateur
private $login;						// User
private $password;					// Password

private $con = null;				// Flux SSH2
private $log = array();

private $tampon = array();			// Enregistrement de certainne valeur pour �conomiser les requ�tes


	/*********************************************************************************
	*
				Les setter
	*
	*********************************************************************************/
	
	
	/**
	 * 
	 * Set server to connect
	 * @param string $host
	 * @param int $port
	 */
	public function setServer($host, $port=22) {
		$this->server = $host;
		$this->port = $port;
	}	
	
	
	/**
	 * 
	 * Set user to connect
	 * @param string $login
	 * @param string $password
	 */
	public function setLogin($login, $password) {
		$this->login = $login;
		$this->password = $password;
	}

	
	/*********************************************************************************
	*
				Les getter 
	*
	*********************************************************************************/
	

	/**
	 * 
	 * an eye on the script with log
	 * @param boolean $getArray
	 * @return array|string
	 */
	public function getLog($getArray = true) {
		$this->log[] = CONSOLE_SSH . "Getting log" . PHP_EOL;
			if ($getArray == false) {
			$this->log[] = CONSOLE_SSH . "Log return brut text" . PHP_EOL;
			$buffer=NULL;
				foreach ($this->log as $key => $debug) {
				$buffer.=$debug;
				}
			return $buffer;
			} else {
			$this->log[] = CONSOLE_SSH . "Log return array" . PHP_EOL;
			return $this->log;
			}
	}
	
	
	/**
	 * 
	 * Auto Connect and auth 
	 * It's easy to use, auto verification
	 */
	public function startIt() { 		
		if ($this->connect()) {
			return  ($this->auth()) ? true : false;
		}
		
	return false;
	}
	
	
	/**
	 * 
	 * Run connection to the select server
	 */
	public function connect() {
	$this->log[] = CONSOLE_SSH . "Welcome to SSH2 Version ".$this->ver." by Christophe BUFFET (developpeur(AROBASE)crystal-web.org)" . PHP_EOL;
	
		if (function_exists('ssh2_connect')) {
			noError(true);
			// Tentative de connection 
			if( !($this->con = ssh2_connect( $this->server, $this->port /* Default 22 */)) ) {
				$this->log[] = ERROR_SSH . "FAIL: unable to establish connection" . PHP_EOL;
				return false;
			} else {
			$this->log[] = CONSOLE_SSH . "Okay: Connected to " . $this->server . PHP_EOL;
			return true;
			}
			noError(false);
		} else {
			$this->log[] = ERROR_SSH . "FAIL: unable to establish connection ('function ssh2_connect not implemented')" . PHP_EOL;
			return false;
		}
	}
	
	public function __destruct() {
		if ($this->con) {
			$this->cmd('echo "EXITING" && exit;');
		}
	}

	
	/**
	 * 
	 * Close connection
	 */
	public function stop() {
	$this->__destruct();
	}
	
	
	/**
	 * 
	 * Run authentification to the select server
	 * @return boolean
	 */
	public function auth() {
		if (function_exists('ssh2_auth_password')) {
			if( !ssh2_auth_password($this->con, $this->login, $this->password) ) {
				$this->log[] = ERROR_SSH . "FAIL: unable to authenticate" . PHP_EOL;
				return false;
			} else {
				$this->log[] = CONSOLE_SSH .  "Okay: logged in... with user " . $this->login . PHP_EOL;
				return true;
			}
		} else {
			$this->log[] = ERROR_SSH . "FAIL: ('function ssh2_auth_password not implemented')" . PHP_EOL;
			return false;
		}
	}




	/*********************************************************************************
	*
				Les commandes 
	*
	*********************************************************************************/
	
	
	/**
	 * 
	 * Run command ;-)
	 * @param string $cmd
	 * @param boolean $console
	 * @return array|boolean
	 */
	public function cmd($cmd, $console=true) {
		if (function_exists('ssh2_exec')) {
			$stream = ssh2_exec($this->con, $cmd );
			if (!$stream) {
			$this->log[] = ERROR_SSH . "FAIL: unable to execute command" . PHP_EOL;
			} else {
			
			$buffer=NULL;
				if ($console == true) {
				$this->addComment( "EXECUTE : command ".$cmd." : " );
				
				// Recuperation des informations recu apres la commande
				stream_set_blocking($stream, true);
				// RaZ Log Console
				$this->tampon['lastLogConsole'] = NULL;
					while ($buf = fread($stream,4096)) {
					$this->log[] = CONSOLE_SSH . $buf;
					$buffer.=$buf;
					$this->tampon['lastLogConsole'] .= $buf;
					}
				
				$this->log[] = $buffer;
				$this->addComment( "END : command ".$cmd." : " );
				}
	
			
			fclose($stream);
			return $buffer;
			}
		} else {
			$this->log[] = ERROR_SSH . "FAIL: ('function ssh2_exec not implemented')" . PHP_EOL;
			return false;
		}
	}
	
	
	/**
	 * 
	 * Search in file
	 * @param string $fileName
	 * @param POSIX $rules
	 */
	public function catSearch($fileName, $rules) {
		// if tampon not exist create and stor filename
		if (!isSet($this->tampon['catSearch']['filename'])) {
		$this->cmd('cat '.$fileName);
				
		$this->tampon['catSearch']['filename']=$fileName;
		$this->tampon['catSearch']['lastlog'] = $this->tampon['lastLogConsole'];
		} elseif($this->tampon['catSearch']['filename']!=$fileName) { // IF tampon[val] != filename, is a different file
		$this->cmd('cat '.$fileName);
		
		$this->tampon['catSearch']['filename']=$fileName;
		$this->tampon['catSearch']['lastlog'] = $this->tampon['lastLogConsole'];
		}
	
		preg_match("#".$rules."#", $this->tampon['catSearch']['lastlog'], $catch);
		$this->log[] = CONSOLE_SSH . "catSearch: " . print_r($catch, true) . PHP_EOL;
		return $catch;
	}
	

	/**
	 * 
	 * Get PID by var name ex: ircd
	 * @param string $app
	 */
	public function getPid($app, $exclude = false) {
		$this->log[] = CONSOLE_SSH . "Get list of process" . PHP_EOL;
		$this->cmd('ps -eo pid,args');
		$consoleLog = preg_replace("#\n|\r\n|\r#", '|', $this->getLog(false));
		$array = explode('|', $consoleLog);

		foreach ($array AS $key => $value) {
			if (strpos($value, $app)) {
				// Recherche du PID
				if (preg_match_all('#([0-9]+)#', $value, $return)) {
					if (!$exclude) {
						$this->addComment( "PID found " . $return[1][0] );
						return (int) $return[1][0];	
					} else {
						if (!strpos($value, $exclude)) {
							$this->addComment( "Not found exclude string:" . $exclude);
							$this->addComment( "PID found " . $return[1][0] );
							return (int) $return[1][0];	
						}
					}
					
				}
			}
		}

	$this->log[] = ERROR_SSH . "Not found process " . $app . PHP_EOL;
	return false;
	}


	/**
	 * 
	 * killed in the process type the name or PID
	 * Another use for this $this->cmd('pkill PROCESS');
	 * @param string $process
	 */
	public function kill($process) {
		if (is_int($process) && $process > 0) {
			$this->addComment(CONSOLE_SSH . "kill is int, kill process");
			$this->cmd('kill -9 '.$process);
			return true;
		} else {
			$this->addComment("kill is var, getting PID");
			$pid = $this->getPid($process);
			
			if (is_int($pid) && $pid > 0) {
				$this->addComment("Kill ".$pid);
				$this->cmd('kill -9 '.$pid);
				return true;
			}
		
		}
	return false;
	}
	
	
	/**
	 * 
	 * Modification des paramettres
	 * ex: sshObject('/home/webradio/Noozs/10300/sc_trans/sc_trans.conf', 'ServerPort=', '10300', ([0-9]+))
	 * result: ServerPort=10300 dans /home/webradio/Noozs/10300/sc_trans/sc_trans.conf
	 * @param string $file
	 * @param string $stringBase
	 * @param string $newString
	 * @param POSIX $rules
	 */	
	public function exchange($file, $stringBase, $newString, $rules='([a-zA-Z0-9\-. ]+)') {
		//Recherche via cat
		$aCatRespon = $this->catSearch($file, $stringBase.$rules);
		//Si on trouve pas on retourne false
		if (!isSet($aCatRespon[1])) {
		$this->log[] = ERROR_SSH . "exchange: Not found ".$stringBase.$rules . " into ".$file . PHP_EOL;
		return false;
		}
		$this->cmd("sed -i s+'".$stringBase.$aCatRespon[1]."'+'".$stringBase.$newString."'+ ".$file);
		$this->addComment($stringBase.$newString . ' into ' . $file);
		return true;
	}
	
	
	/**
	 * 
	 * Little methode to run a cmd lauch/run app
	 * If you run any app. With $this->cmd()
	 * make, sure you use $this->cmd('./meApp', true)
	 * @param string $meapp
	 */
	public function run($meapp) {
		$this->log[] = CONSOLE_SSH . "Run command silent ".$meapp . PHP_EOL ;
		$this->cmd($meapp, false);
	}
	
	
	/*********************************************************************************
	*
				Les logs
	*
	*********************************************************************************/
	
	
	
	/**
	 * 
	 * For a much cleaner job, commentary used
	 * @param string $comment
	 */
	public function addComment($comment) {
		$this->log[] = COMMENT_SSH . $comment . PHP_EOL;
	}
	
}

?>
<?php 
class Session{

	public function __construct(){
		if(!isset($_SESSION))
		{
			Log::setLog('Start session', 'Session');
			session_start();
		}
		
		if ($this->isLogged())
		{
			if (!isSet($_SESSION['user']->time))
			{
				$_SESSION['user']->time = time();
				$_SESSION['user']->ip = Securite::ipX();
			//	setcookie('sess', $_SESSION['user']->idmember, (time() + (86400 * 30)));
			}
			elseif ($_SESSION['user']->time+3600 < time())
			{		
				$this->logout();
				$this->setFlash('Session expirer', 'warning');
				Router::redirect();
				return;
			}
			elseif ($_SESSION['user']->ip != Securite::ipX())
			{
				$this->logout();
				$this->setFlash('Votre adresse IP à changé, par mesure de sécurite nous avons fermer la connection.', 'warning');
				Router::redirect();
				return;
			}
			
			$_SESSION['user']->time = time();
			
			if (__DEV_MODE)
			{
				if (isSet($_GET['unsetsession']))
				{
				unset($_SESSION);
				}
			}
		}
		
		if ( !isSet($_SESSION['token']) )
		{
			$this->makeToken();
		}
	}
	
	/***************************************
	*	Token
	***************************************/
	public function makeToken()
	{
		Log::setLog('Token assigned', 'Session');
		$_SESSION['token'] = md5(time()*rand()+magicword);
	}
	
	public function token()
	{
		if (isSet($_GET['token']))
		{
			return ($_GET['token'] === $_SESSION['token']) ? true : false;
		}
		return false;
	}
	
	public function getToken()
	{
		return $_SESSION['token'];
	}
	
	/***************************************
	*	Flash info
	***************************************/
	public function setFlash($message,$type = 'success'){
		$_SESSION['flash'][] = array(
			'message' => $message,
			'type'	=> $type
		); 
	}

	public function flash(){
		if(isset($_SESSION['flash'])){
			
			$html = NULL;
			foreach($_SESSION['flash'] AS $k => $v)://&times;
			$html .= '<div class="alert-message '.$v['type'].' fade in" data-alert="alert"><a class="close" href="#"></a><p>'.$v['message'].'</p></div>'; 
			endforeach;
				
			$_SESSION['flash'] = array(); 
			return $html; 
		}
	}

	
	/***************************************
	*	Membre
	***************************************/
	public function isLogged(){
		return isset($_SESSION['user']->levelmember);
	}


	
	public function logout(){
		if (isset($_SESSION['user']))
		{
		unset($_SESSION['user']);
		}
	}
	
	public function user($key)
	{
		switch($key)
		{

		case 'id':
			if (isset($_SESSION['user']->idmember))
			{
			return $_SESSION['user']->idmember;
			}
		break;
		case 'login':
			if (isset($_SESSION['user']->loginmember))
			{
			return $_SESSION['user']->loginmember;
			}
		break;
		case 'level':
			if (isset($_SESSION['user']->levelmember))
			{
			return $_SESSION['user']->levelmember;
			}
			else
			{
			return 1;
			}
		break;
		case 'group':
			if (isset($_SESSION['user']->groupmember))
			{
			return trim($_SESSION['user']->groupmember, '|');
			}
			else
			{
			return 0;
			}
		break;
		/***************************************
		*	Action par defaut
		***************************************/
		default:
			if($this->read('user')){
				if(isset($this->read('user')->$key))
				{
					return $this->read('user')->$key; 
				}
				else
				{
					return false;
				}
			}
			return false;
		
		break;
		}

	}

	
	
	
	
	

	public function write($key,$value){
		$_SESSION[$key] = $value;
	}
	
	public function del($key)
	{
		unset($_SESSION[$key]);
	}
	public function __set($index, $value)
	{
	$_SESSION[$index] = $value;
	}

	public function __get($index)
	{
	return isSet($_SESSION[$index]) ? $_SESSION[$index] : false;
	}

	public function read($key = null){
		if($key){
			if(isset($_SESSION[$key])){
				return $_SESSION[$key]; 
			}else{
				return false; 
			}
		}else{
			return $_SESSION; 
		}
	}

}
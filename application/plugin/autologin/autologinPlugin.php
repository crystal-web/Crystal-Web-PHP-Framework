<?php
Class autologinPlugin extends PluginManager{
	
	private $show = false;
	public function onEnabled()
	{
		Log::setLog('Enabled', 'autologin');
		
		if ($this->mvc->Session->read('autologin'))
		{
			if ($this->mvc->Session->read('autologin') > (time() - 5))
			{
				return false;
			}
		}
		
		
		
		if (isSet($_COOKIE['__utmo']) && $this->mvc->Request->controller != 'auth' && !$this->mvc->Session->isLogged())
		{
		Log::setLog('Cookie exist', 'autologin');
		
			$cok = explode('.', $_COOKIE['__utmo']);
			if (isSet($cok[0]) && isSet($cok[1]))
			{
				Log::setLog('Logique ok', 'autologin');
				$id = (int) $cok[0];
				$mMember = loadModel('Member');
				
				$prepare = array(
					'conditions' => array('idmember' => $id)
					);
				$search = $mMember->findFirst($prepare);
				
				if ( $search )
				{
				Log::setLog('Member found', 'autologin');
					
					$p = new PassMan($search->password . magicword);
					$pw = $p->Decrypte($cok[1]);
					
					Log::setLog('Decrypting ' . $pw, 'autologin');
					
					if ((int) $pw == $search->idmember)
					{
						if ($this->show)
						{
							$this->mvc->Session->setFlash('Relog');
						}
						
						Log::setLog('Wirting session ', 'autologin');
						$this->mvc->Session->write('user', $search);
						
						$search->ip = Securite::ipX();
						$search->lastactivitymember =  time();
						$mMember->save($search);
						Router::refresh();
					}
				}
			}
		}
	}
	
	

	public function onMemberLogin()
	{

		if ($this->mvc->Session->isLogged())
		{
			
			if (isSet($this->mvc->Request->data->connect) or isSet($this->mvc->Request->data->connection_auto))
			{
				Log::setLog('Request autologin ', 'autologin');
				
				$p = new PassMan($_SESSION['user']->password . magicword);
			
				$pw = $_SESSION['user']->idmember.'.' . $p->Crypte($_SESSION['user']->idmember) . '.' . $_SESSION['user']->firstactivitymember . '.' . $_SESSION['user']->lastactivitymember;
				Log::setLog('Cookie value ' . $pw, 'autologin');
				setcookie('__utmo', $pw, (time() + (86400 * 30)));
				
				if ($this->show)
				{
					$this->mvc->Session->setFlash('AutoRelogOk');
$_SESSION['log'] = Log::console();
				}
			}
			else
			{
				if ($this->show)
				{
					$this->mvc->Session->setFlash('AutoRelogNop');
				}
			}

		}

	}
	
	
	public function onMemberLogout()
	{
		$this->mvc->Session->write('autologin', time());
		setcookie('__utmo');
		setcookie('__utmo', '', 0, '/');
		unset($_COOKIE['__utmo']);
		
		if ($this->show)
		{
			$this->mvc->Session->setFlash('Autologout');
		}
	}

}
<?php
require __APP_PATH . DS . 'plugin' . DS . 'lock' . DS .'Starter.class.php';

Class lockPlugin extends PluginManager{

public function onEnabled()
{

    // Chargement de l'objet
    $cache = new Cache('starter');
    $oStarter = new Starter($cache->getCache());


    //var_dump($_SESSION['starter'], $oStarter->debug());
    //if ($oStarter->getStatus() == false)

    if ($oStarter->getStatus() == 'enabled')
    {
		// initialisation
   		$noload = $isAdd = false;

        if (isSet($this->mvc->Request->data->starter))
        {
            // Retourne un table, si l'adresse est trouve
            $status = $oStarter->getUserStatus($this->mvc->Request->data->starter);

            // L'adresse est trouvé, on recherche la valeur du droit
            if (is_array($status))
            {
                $_SESSION['starter'] = ($status['a']) ? true : false;
            }
            // L'adresse n'est pas connu, on l'enregistre
            else
           {
            	$isAdd = $oStarter->addMail($this->mvc->Request->data->starter);
            }
            $cache->setCache($oStarter->getParam());
        }

        $_SESSION['starter'] = (isSet($_SESSION['starter'])) ? $_SESSION['starter'] : false;


        if ($oStarter->getTime2open() != 0)
        {
        $dureeSec   = $oStarter->getTime2open() - time();
            if (1 > $dureeSec)
            {
                $oStarter->setStatus(false);
                $noload = true;
                $cache->setCache($oStarter->getParam());
            }
        header('Refresh: '.$dureeSec);
        }


        if (!$_SESSION['starter'] or $noload)
        {
            // Enclenche la temporisation de sortie
            //ob_start();

			$this->mvc->Template->textForm  = $oStarter->getDisplayTextForm();
			$this->mvc->Template->display   = $oStarter->getDisplayTime();
			$this->mvc->Template->time      = $oStarter->getTime2open();
			$this->mvc->Template->iso       = date('c', $oStarter->getTime2open());
			
			
			
			$this->mvc->Template->isAdd = $isAdd;
			
			
			$this->mvc->Template->setPath(__APP_PATH . DS . 'plugin' . DS . 'lock');
			$this->mvc->Template->title = $oStarter->getTitle();
			$this->mvc->Template->message = $oStarter->getMessage();
			$this->mvc->Template->show('default');

            exit();
        }
    }
}



/**
 * 
 * Administration du plugin
 */
public function locksiteSetting()
{
	if (!$this->mvc->Acl->isAllowed())
	{
	$this->mvc->Session->setFlash('Vous n\'avez pas les autorisations nécéssaires', 'error');
	Router::redirect();
	}
	
$cache = new Cache('starter');
$oStarter = new Starter($cache->getCache());

$menu = isSet($this->mvc->Request->params['menu']) ? $this->mvc->Request->params['menu'] : 'default';

echo '<style>
ul#locksite li { 
display : inline;
padding : 0 0.5em;
}
ul#locksite {
list-style-type : none;
}
</style>';

echo '<ul id="locksite">
	<li><a href="'.Router::url('plugin/manager/slug:lock').'">Accueil</a></li>
	<li><a href="'.Router::url('plugin/manager/slug:lock/menu:adminmail').'">Ajout de mail admin</a></li>
	<li><a href="'.Router::url('plugin/manager/slug:lock/menu:sendmail').'">Envoie d\'un e-mail aux adresses enregistrées</a></li></ul>';

switch ($menu):
	/**
	 *   Ajout d'un e-mail admin
	 */
	case 'adminmail':
	
		if (isSet($this->mvc->Request->data->mail))
		{
			$newmail = $this->mvc->Request->data->mail;
			if ($oStarter->addMail($newmail, true))
			{
		        /***************************************
		        *   On enregistre dans le cache
		        ***************************************/
		        $cache->setCache($oStarter->getParam());
				$this->mvc->Session->setFlash('E-mail ajouté pour accès');
			}
	
			//debug($oStarter->getMail());
		}
		
		$this->mvc->Template->mailList = $oStarter->getMail();
		$this->mvc->Template->adminMail = $oStarter->getMailAdmin();
		$this->mvc->Template->setPath(__APP_PATH . DS . 'plugin' . DS . 'lock');
		$this->mvc->Template->show('adminMail');
	break;


	/**
	 * Envois de mail a tout les adresse enregistré
	 */
	case 'sendmail':
		
		$whait = 2;
		$this->mvc->Template->whait = $whait;
		
		$nbMailParPage = 15;
		$this->mvc->Template->nbMailParPage = $nbMailParPage;
		
		if ( $this->mvc->Request->title && $this->mvc->Request->content )
		{
			$title = clean($this->mvc->Request->title, 'str'); 	
			$content = clean($this->mvc->Request->content, 'bbcode');

			$list = $oStarter->getMail();
			$mail = array();
			foreach ($list AS $k => $v)
			{
				$mail[] = $k;	
			}
			
			$this->mvc->Session->write('lock', array(
				'title' => $title,
				'content' => $content,
				'currentPage' => 0,
				'mail' => $mail,
				'total' => count($mail),
				'time' => time()
				));
				

		Router::redirect(Router::url('plugin/manager/slug:lock/menu:sendmail').'?send=1');	
		}
		
		
		if (isSet($_GET['send']))
		{
			$params = $this->mvc->Session->read('lock');
			$startIt = ($nbMailParPage * $params['currentPage']);
			
			$txt = (time() - $params['time']). ' secondes '.'<br>' .$startIt . ' / ' . $params['total'] . '<ul>';
			
	
			for ($m=$startIt; $m<($nbMailParPage+$startIt); $m++)
			{

				
				if (isSet($params['mail'][$m]))
				{
					$mail_send = new Mail('['.$this->mvc->Page->getSiteTitle().'] '. $params['title'] ,$params['content'], $params['mail'][$m], ADMIN_MAIL);
			
					if ($mail_send->sendMailHtml())
					{
						$txt .= '<li style="color:green;">' . $params['mail'][$m] . '</li>';
					}
					else
					{
						$txt .= '<li style="color:red;">' . $params['mail'][$m] . '</li>';
					} 
					
					unset($params['mail'][$m]);
				}
				else
				{
					$this->mvc->Session->setFlash('Job succes in ' .(time() - $params['time']). ' secondes');
					Router::redirect('plugin/manager/slug:lock/menu:sendmail');
				}	
			}
			
			$txt .= '</ul>';
			
			$params['currentPage']++;
			$this->mvc->Session->write('lock', $params);
			
			header('Refresh: '.$whait.';url='.Router::url('plugin/manager/slug:lock/menu:sendmail').'?send='.time());
			
			
			
			echo $txt;
			/*
			$params['currentPage'] = $params['currentPage']+1;
			$this->mvc->Session->write('lock', $params);*/
			die;
		}
		
		
		
		
		$this->mvc->Template->mailList = $oStarter->getMail();
		$this->mvc->Template->setPath(__APP_PATH . DS . 'plugin' . DS . 'lock');
		$this->mvc->Template->show('sendMail');
	break;
	
	
	/**
	 *   Par defaut
	 */
	default:
	
	    /***************************************
	    *   Traitement lors de l'envois du formulaire
	    ***************************************/
	    if (isSet($this->mvc->Request->data->time_delay))
	    {
	        /***************************************
	        *   On affiche le decompteur ?
	        ***************************************/
	        if ($this->mvc->Request->data->display_delay == 0) { $oStarter->setDisplayTime(false); }
	        else { $oStarter->setDisplayTime(true); }
	
	
	        switch ($this->mvc->Request->data->time_delay)
	        {
	        /***************************************
	        *   Maintenance active ?
	        ***************************************/
	        case 'no':
	            $oStarter->setStatus(false);
	        break;
	        /***************************************
	        *   Combien de temps en sec ?
	        ***************************************/
	        case 'sec':
	            $oStarter->setStatus(true);
	            /***************************************
	            *   Si infini, on affiche pas le decompteur
	            ***************************************/
	            if ($_POST['maintain'] == 'inf')
	            {
	            $oStarter->setTime2open(0);
	            $oStarter->setDisplayTime(false);
	            }
	            else
	            {
	            $oStarter->setTime2open(time()+(int) $this->mvc->Request->data->maintain);
	            }
	        break;
	        /***************************************
	        *   Jusqu'a quand ?
	        ***************************************/
	        case 'date':
	            $oStarter->setStatus(true);
	            $oStarter->setTime2open(
	                mktime(
	                    date("H"),
	                    date("i"),
	                    date("s"),
	                    (int) $_POST['m'],
	                    (int) $_POST['j'],
	                    (int) $_POST['a']
	                    )
	                );
	        break;
	        }
	
	
	        /***************************************
	        *   Enregistre le message a afficher
	        ***************************************/
	        if (isSet($this->mvc->Request->data->message))
	        {//stripcslashes(html_entity_decode(stripspace(
	        $oStarter->setMessage($this->mvc->Request->data->message);
	        }
	
	        if (isSet($this->mvc->Request->data->textForm))
	        {
	        $oStarter->setDisplayTextForm($this->mvc->Request->data->textForm);
	        }
	
	        /***************************************
	        *   On enregistre dans le cache
	        ***************************************/
	        $cache->setCache($oStarter->getParam());
	       // debug($oStarter->getParam());
	    }
	
	
	
	    $this->mvc->Template->checkNon=null;
	    $this->mvc->Template->checkTo=null;
	    $this->mvc->Template->checkSec=null;
	    $this->mvc->Template->checkDate=null;
	
	    $time = $oStarter->getTime2open();
	    $this->mvc->Template->time = $time;
	    if ($oStarter->getStatus('enabled'))
	    {
	    	if ($time == 0)
	    	{
	    	$this->mvc->Template->checkSec=' checked="checked"';
	    	}
	        elseif ($time < 57600)
	        {
	        $this->mvc->Template->checkTo=' checked="checked"';
	        }
	        else
	        {
	        $this->mvc->Template->checkSec=' checked="checked"';
	        }
	
	    }
	    else
	    {
	    $this->mvc->Template->checkNon=' checked="checked"';
	    }
	
	    //ob_start();
	$this->mvc->Template->setPath(__APP_PATH . DS . 'plugin' . DS . 'lock');
	$this->mvc->Template->textForm = $oStarter->getDisplayTextForm();
	$this->mvc->Template->displayDelay = $oStarter->getDisplayTime();
	$this->mvc->Template->message = $oStarter->getMessage();
	$this->mvc->Template->show('admin');
	break;
endswitch;




if (isSet($_GET['debug']))
{
	debug($oStarter->debug());
}

// Restore template
$this->mvc->Template->setPath(__APP_PATH);
}
/* end locksiteSetting*/


}









?>

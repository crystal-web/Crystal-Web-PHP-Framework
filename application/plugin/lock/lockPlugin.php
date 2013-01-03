<?php
require __APP_PATH . DS . 'plugin' . DS . 'lock' . DS .'Starter.class.php';

Class lockPlugin{

public function onEnabled()
{

    // Chargement de l'objet
    $cache = new Cache('starter');
    $oStarter = new Starter($cache->getCache());
	$request = Request::getInstance();
/*
		$mTop = loadModel('Topsites');
		$mTop->setSiteslug('devphpme');
		
		if ($mTop->isActive())
		{
			$mTop->isUnique(Securite::ipX());
		}//*/
		
    //var_dump($_SESSION['starter'], $oStarter->debug());
    //if ($oStarter->getStatus() == false)

    if ($oStarter->getStatus() == 'enabled')
    {
		// initialisation
   		$noload = $isAdd = false;

		
        if (isSet($request->data->starter))
        {
            // Retourne un table, si l'adresse est trouve
            $status = $oStarter->getUserStatus($request->data->starter);

            // L'adresse est trouvé, on recherche la valeur du droit
            if (is_array($status))
            {
                $_SESSION['starter'] = ($status['a']) ? true : false;
            }
            // L'adresse n'est pas connu, on l'enregistre
            else
           {
            	$isAdd = $oStarter->addMail($request->data->starter);
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
        	if (isset($_GET['bypass'])){
        		$_SESSION['starter'] = true;
        	}
			$template = Template::getInstance();
			$template->setPath(__APP_PATH . DS.'plugin'.DS.'lock'.DS.'views');
			
			$template->textForm  = $oStarter->getDisplayTextForm();
			$template->display   = $oStarter->getDisplayTime();
			$template->time      = $oStarter->getTime2open();
			$template->iso       = date('c', $oStarter->getTime2open());
			$template->isAdd = $isAdd;
			$template->title = $oStarter->getTitle();
			$template->message = $oStarter->getMessage();
			$template->show('default');

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

	$acl = AccessControlList::getInstance();
	$session = Session::getInstance();
	$page = Page::getInstance();
	$request = Request::getInstance();
	$template = new Template();
	$template->setPath(__APP_PATH . DS.'plugin'.DS.'lock'.DS.'views');
	
	if (!$acl->isAllowed())
	{
		$session->setFlash('Vous n\'avez pas les autorisations nécéssaires', 'error');
		Router::redirect();
	}
	
$cache = new Cache('starter');
$oStarter = new Starter($cache->getCache());


$menu = isSet($request->params['menu']) ? $request->params['menu'] : 'default';

echo '<style>
ul#locksite li { 
display : inline;
padding : 0 0.5em;
}
ul#locksite {
list-style-type : none;
}
</style>';

echo '<ul class="tabs">
	<li><a href="'.Router::url('plugin/manager/slug:lock').'">Accueil</a></li>
	<li><a href="'.Router::url('plugin/manager/slug:lock/menu:adminmail').'">Ajout de mail admin</a></li>
	<li><a href="'.Router::url('plugin/manager/slug:lock/menu:sendmail').'">Envoie d\'un e-mail aux adresses enregistrées</a></li></ul>';

switch ($menu):
	/**
	 *   Ajout d'un e-mail admin
	 */
	case 'adminmail':
	
		if (isSet($request->data->mail))
		{
			$newmail = $request->data->mail;
			if ($oStarter->addMail($newmail, true))
			{
		        /***************************************
		        *   On enregistre dans le cache
		        ***************************************/
		        $cache->setCache($oStarter->getParam());
				$session->setFlash('E-mail ajouté pour accès');
			}
		}
		
		
		$template->mailList = $oStarter->getMail();
		$template->adminMail = $oStarter->getMailAdmin();
		$template->show('adminMail');
	break;


	/**
	 * Envois de mail a tout les adresse enregistré
	 */
	case 'sendmail':
		
		$whait = 2;

		
		$template->whait = $whait;
		$nbMailParPage = 15;
		$template->nbMailParPage = $nbMailParPage;
		
		
		if ( isset($request->data->title, $request->data->message) )
		{
			$title = clean($request->title, 'str'); 	
			$content = clean($request->message, 'bbcode');

			$list = $oStarter->getMail();
			$mail = array();
			foreach ($list AS $k => $v)
			{
				$mail[] = $k;	
			}
			
			$session->write('lock', array(
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
			$params = $session->read('lock');
			$startIt = ($nbMailParPage * $params['currentPage']);
			
			$txt = (time() - $params['time']). ' secondes '.'<br>' .$startIt . ' / ' . $params['total'] . '<ul>';
			
	
			for ($m=$startIt; $m<($nbMailParPage+$startIt); $m++)
			{

				
				if (isSet($params['mail'][$m]))
				{
					$mail_send = new Mail('['.$page->getSiteTitle().'] '. $params['title'] ,$params['content'], $params['mail'][$m], ADMIN_MAIL);
			
					if ($mail_send->sendMailHtml())
					{
						$txt .= '<li style="color:green;">' . $params['mail'][$m] . '</li>';
					}
					else
					{
						$txt .= '<li style="color:red;">' . $params['mail'][$m] . '</li>';
					}
					$txt .= '<li style="color:red;">' . $params['mail'][$m] . '</li>';
					unset($params['mail'][$m]);
				}
				else
				{
					$session->setFlash('Job succes in ' .(time() - $params['time']). ' secondes');
					Router::redirect('plugin/manager/slug:lock/menu:sendmail');
				}	
			}
			
			$txt .= '</ul>';
			
			$params['currentPage']++;
			$session->write('lock', $params);
			
			header('Refresh: '.$whait.';url='.Router::url('plugin/manager/slug:lock/menu:sendmail').'?send='.time());
			
			
			
			echo $txt;
			/*
			$params['currentPage'] = $params['currentPage']+1;
			$this->mvc->Session->write('lock', $params);*/
			die;
		}
		
		
		
		
		$template->mailList = $oStarter->getMail();

		$template->show('sendMail');
	break;
	
	
	/**
	 *   Par defaut
	 */
	default:

	    /***************************************
	    *   Traitement lors de l'envois du formulaire
	    ***************************************/
	    if (isSet($request->data->time_delay))
	    {
	        /***************************************
	        *   On affiche le decompteur ?
	        ***************************************/
	        if ($request->data->display_delay == 0) { $oStarter->setDisplayTime(false); }
	        else { $oStarter->setDisplayTime(true); }
	
	
	        switch ($request->data->time_delay)
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
					$oStarter->setTime2open(time()+(int) $request->data->maintain);
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
	        if (isSet($request->data->message))
	        {//stripcslashes(html_entity_decode(stripspace(
	        $oStarter->setMessage($request->data->message);
	        }
	
	        if (isSet($request->data->textForm))
	        {
	        $oStarter->setDisplayTextForm($request->data->textForm);
	        }
	
	        /***************************************
	        *   On enregistre dans le cache
	        ***************************************/
	        $cache->setCache($oStarter->getParam());
	       // debug($oStarter->getParam());
	    }
	
	
	
	    $template->checkNon=null;
	    $template->checkTo=null;
	    $template->checkSec=null;
	    $template->checkDate=null;
	
	    $time = $oStarter->getTime2open();
	    $template->time = $time;
	    if ($oStarter->getStatus('enabled'))
	    {
	    	if ($time == 0)
	    	{
	    		$template->checkSec=' checked="checked"';
	    	}
	        elseif ($time < 57600)
	        {
	        	$template->checkTo=' checked="checked"';
	        }
	        else
	        {
	        	$template->checkSec=' checked="checked"';
	        }
	
	    }
	    else
	    {
	    	$template->checkNon=' checked="checked"';
	    }
	
	    //ob_start();
	//$template->setPath(__APP_PATH . DS . 'plugin' . DS . 'lock' . DS . 'views');
	$template->textForm = $oStarter->getDisplayTextForm();
	$template->displayDelay = $oStarter->getDisplayTime();
	$template->message = $oStarter->getMessage();
	$template->show('admin');
	//$template->setPath(__APP_PATH . DS . 'views');
	break;
endswitch;




	if (isSet($_GET['debug']))
	{
		debug($oStarter->debug());
	}

}
/* end locksiteSetting*/

}

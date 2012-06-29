<?php
Class Config {
	
public $mvc;

// Model SQL
private $model;
private $modelController;

private $html;

private $compteur = 1;

	public function __construct(mvc $mvc)
	{
		$this->mvc = $mvc;
		$this->model = new ConfigModel();
		$this->modelController = $this->model->getController($this->mvc->getController(), $this->mvc->getAction());
		$this->modelController->params = unserialize($this->modelController->params);
		
		if (isSet($this->mvc->Request->data->controller) && isSet($this->mvc->Request->data->action) )
		{
			if ( $this->mvc->Acl->isAllowed($this->mvc->getController() . '.' . $this->mvc->getAction()) )
			{
				// Suppression de controller
				unset($this->mvc->Request->data->controller);
				// Suppression de action
				unset($this->mvc->Request->data->action);

				// Chargement de la class 
				// et remplissage
				$data = new stdClass();
				if (isSet($this->modelController->id))
				{
					$data->id = $this->modelController->id;
				} 
				
				
				$data->controller = $this->mvc->getController();
				$data->action = $this->mvc->getAction();
				$data->params = serialize($this->mvc->Request->data);
				// Saugarde des infos
				$this->model->save($data);
				// Destruction de data
				unset($data);
				
				// Petit message ^^
				echo '<html>
					<head>
						<title>Configuration sauvegarder</title>
						<link rel="stylesheet" href="http://cdn.crystal-web.org/files/css/bootstrap/1.4.0/bootstrap.min.css">
					</head>
					<body style="background: #3D3D3D;font: 35px \'Lucida Grande\', Arial, sans-serif;">
						<div style="width: 700px;margin-left: auto;margin-right: auto;zoom: 1;">
							<div class="row" style="margin-top:30px;">
								<div class="span12">
						
									<div style="background: #E9EAEE; border: 2px solid white; padding: 25px 0 25px 0; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; text-align: center;">
										Configuration sauvegarder
									</div>
									<div style="margin-top:35px;">
									<p align="center">
										<button onclick="self.close();return false;" class="btn success">Fermer</button>
									</p>
									</div>
								</div>
							</div>
						</div>
					</body>
				</html>';
				die;
			}
		}
	}

	
	
	public function input($name, $label, $options = array())
	{
		$name = clean($name, 'str');
		if ($label != 'hidden') {$this->compteur++;}
		
		if (isset($this->modelController->params->$name))
		{
			$options['value'] = $this->modelController->params->$name; 
			$this->html .= $this->mvc->Form->input($name, $label, $options);
		}
		else
		{
			$this->html .= $this->mvc->Form->input($name, $label, $options);
		}
		
	}
	
	
	
	
	public function make($text = 'Configuration')
	{
		$this->input('controller', 'hidden', array('default' => $this->mvc->getController()));
		$this->input('action', 'hidden', array('default' => $this->mvc->getAction()));
		$this->input('Enregistrer', 'submit', array('type' => 'submit', 'class' => 'btn info'));
		
		$height = ($this->compteur > 12) ? 600 : ($this->compteur * 45) + 20; //94;
		
		$content = addslashes($this->html);
		$content = preg_replace('#\n#', '', $content);
		$content = preg_replace('#script>#', 'scr\' + \'ipt>', $content);
		$content = preg_replace('#<script#', '<scr\' + \'ipt', $content);

		
		$html = '<script type="text/javascript">';
		$html .= 'function configWin()';
		$html .= '{';
		$html .= 'myWindow=window.open(\'\',\'\',\'menubar=no, status=no, scrollbars=no, menubar=no, width=720, height='.$height.'\');';
		$html .= 'myWindow.document.write(\'<html><head><link rel="stylesheet" href="'.__CDN.'/files/css/bootstrap/1.4.0/bootstrap.min.css"></head><body>\');';
		$html .= 'myWindow.document.write(\'<div style="width: 700px;margin-left: auto;margin-right: auto;zoom: 1;"><div class="row" style="margin-top:30px;"><div class="span12">\');';
		$html .= 'myWindow.document.write(\'<form method="post" action="' . __CW_PATH . $this->mvc->getUrl() . '">'.$content.'</form>\');';
		
		$html .= 'myWindow.document.write(\'</div></div></div>\');';
		$html .= 'myWindow.document.write(\'</body></html>\');';
		$html .= 'myWindow.focus();';
		$html .= '}';
		$html .= '</script>';
		$this->mvc->Page->setHeader($html);
		return '<a onclick="configWin()">'.$text.'</a>';
	}
	
	
	
	public function getConfig()
	{
		return  $this->modelController->params;
	} 
	
}



Class ConfigModel extends Model {

	/**
	 * 
	 * Installation automatique
	 */
	public function install()
	{
		$this->query("CREATE TABLE ".__SQL."_Config (
			`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
			`controller` VARCHAR( 256 ) NOT NULL ,
			`params` TEXT NOT NULL ,
			PRIMARY KEY (  `id` )
			) ENGINE = MYISAM ;");
	}
	
	
	/**
	 * 
	 * Recherche dans la base de donnée
	 * la configuration du controller et de son action
	 * @param string $controller
	 * @param string $action
	 * @return object stdClass
	 */
	public function getController($controller, $action)
	{
		$f = array('conditions' => array('controller' => $controller, 'action' =>  $action));
		return $this->findFirst($f);
	}

}
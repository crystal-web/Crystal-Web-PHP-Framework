<?php
function _format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YB';
    }
}


Class mediaController extends Controller{
private $strict = false;
private $whiteList = array('jpg', 'jpeg', 'mp3', 'gif', 'png', 'pdf', 'ogg', 'wav', 'sql', 'zip', 'avi');


public function index()
{

	if (!$this->mvc->Acl->isAllowed())
	{
		return Router::redirect('');
	}
	
	$this->mvc->Page->setBreadcrumb('media', 'Media')
					->setPageTitle('M&eacute;diath&egrave;que')
					->body('onunload="exitwindow();"');
	$mediaModel = $this->loadModel('Media');
	
	$find=array(
//		url	type	mime
	);

	$this->mvc->Template->listFiles = $mediaModel->find();
	$this->mvc->Template->show('media/pupload');
	//$this->mvc->Template->show('media/index');	
}


public function browser()
{
	if (!$this->mvc->Acl->isAllowed())
	{
		return Router::redirect('');
	}
	$this->mvc->Page->setPageTitle('Navigateur');
	$this->mvc->Page->setBreadcrumb('media/browser', 'browser');
	
	$mMedia = $this->loadModel('Media');

	if (!isSet($this->mvc->Request->params['type']))
	{
		$prepare = array(
			'fields' => 'type , COUNT(type) AS countType',
			'order' => 'type ASC',
			'group' => 'type'
			);
		$this->mvc->Template->group = $mMedia->find($prepare);
		
		$this->mvc->Template->list = $mMedia->getList();
		$this->mvc->Template->show('media/browser');
	}
	elseif(isSet($this->mvc->Request->params['type']) && !isSet($this->mvc->Request->params['sub']))
	{
		
		$type = $this->mvc->Request->params['type'];
		$this->mvc->Page->setBreadcrumb('media/browser/type:' . $type, $type);
		
		$prepare = array(
			'fields' => '* , CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType, COUNT(type) AS countSubType',
			'order' => 'subType ASC',
			'group' => 'subType',
			'conditions' => array('type' => $type)
			);
		$this->mvc->Template->group = $mMedia->find($prepare);
		
		$this->mvc->Template->list = $mMedia->getListByType($type);
		$this->mvc->Template->show('media/browser-type');
	}
	elseif(isSet($this->mvc->Request->params['type']) && isSet($this->mvc->Request->params['sub']))
	{
		$type = $this->mvc->Request->params['type'];
		$subType = $this->mvc->Request->params['sub'];
		
		$this->mvc->Page->setBreadcrumb('media/browser/type:' . $type, $type);
		$this->mvc->Page->setBreadcrumb('media/browser/type:' . $type . '/sub:' . $subType, $subType);
		
		$prepare = array(
			'fields' => '* , CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType, COUNT(type) AS countSubType',
			'order' => 'subType ASC',
			'group' => 'subType',
			'conditions' => array('type' => $type)
			);
		$this->mvc->Template->group = $mMedia->find($prepare);
		
		$this->mvc->Template->list = $mMedia->getListBySubType($type.'/'.$subType);
		$this->mvc->Template->show('media/browser-type');
	}	
}


public function fileinfo()
{
	if (!$this->mvc->Acl->isAllowed())
	{
		return Router::redirect('');
	}
	
	if (!isSet($this->mvc->Request->params['id']))
	{
		return $this->browser();
	}
	
	$this->mvc->Page->setPageTitle('Fileinfo');
	$this->mvc->Page->setBreadcrumb('media/browser', 'browser');
	
	$id = (int) $this->mvc->Request->params['id'];
	
	$mMedia = loadModel('Media');
	
	$fileinfo = $mMedia->getId($id);
	
	// Test si la requete abouti si pas on charge browser
	if (!$fileinfo)
	{
		return $this->browser();
	}
	// Recupère l'adresse du fichier
	$fileaddr = './media/' . $fileinfo->mime . '/' . $fileinfo->name;
	
	
	$this->mvc->Template->fileaddr = $fileaddr;
	$this->mvc->Template->fileinfo = $fileinfo;
	$this->mvc->Template->show('media/fileinfo');
}



private function makeIt($id, $name, $type, $mime)
{
ob_start();
?>
<form>
<div class="clearfix">
<label>
	<?php
	switch ($type)
	{
	case 'image':
	list($width, $height, $type, $attr) = getimagesize(__CW_PATH . '/media/' . $mime . '/' . $name);
	
		if (preg_match('#gif#', $name))
		{
		?>
		<a href="<?php echo  __CW_PATH . '/media/' . $mime . '/' . $name; ?>" onclick="window.open(this.href, 'pop<?php echo $id;?>','menubar=no, status=no, scrollbars=yes, width=<?php echo $width;?>, height=<?php echo $height;?>');return false;"><img src="<?php echo  __CW_PATH . '/media/' . $mime . '/' . $name; ?>" alt="" /></a>
		<?php
		}
		elseif (preg_match('#(png|jpe?g)#', $name))
		{
		?>
		<a href="<?php echo  __CW_PATH . '/media/' . $mime . '/' . $name; ?>" onclick="window.open(this.href, 'pop<?php echo $id;?>','menubar=no, status=no, scrollbars=yes, width=<?php echo $width;?>, height=<?php echo $height;?>');return false;"><img src="<?php echo  __CW_PATH . '/media.php?thumb=/'.$mime.'/' . $name; ?>" alt="" /></a>
		<?php
		}
		else
		{
		echo 'no matching ext';
		}
	break;

	case 'audio':
	echo '<img src="' . __CW_PATH . '/media/audio.png" alt="" /> 
<audio controls="controls" controls="true" preload="true">
  <source src="' . __CW_PATH . '/media/' . $mime. '/' . $name . '" type="'.$mime.'" />
  Your browser does not support the audio tag.
</audio>
';
	break;
	}
	?>	
</label>
	<div class="input">
		<div class="input-prepend">
		<span class="add-on">URL: </span>
			<input type="text" value="<?php echo __CW_PATH . '/media/' . $mime . '/' . $name; ?>" >
			<a href="" class="btn del">Supprimer</a>
		</div>
	</div>
</div>
</form>
<?php
return ob_get_clean();
}

/**
 * Enregistre le fichier uploader via Ajax
 * @see Controller::ajax()
 */
public function addfile()
{
	if (!$this->mvc->Acl->isAllowed())
	{
		die('{"error":true, "message": "Droit d\'envois requis"}'); 
	}
	
	$this->mvc->Page->setLayout('empty');
	if (isSet($_FILES['file']))
	{

		$file = $_FILES['file'];
		$name = $file['name'];
		if(filesize($file['tmp_name']) > 1000000000)
		{
			die('{"error":true, "message": "Image trop grande"}'); 
		}

		if(file_exists('media/'.$_FILES['file']['type'].'/'.$name))
		{
			die('{"error":true, "message": "Le fichier existe déjà"}'); 
		}

		/*if(!preg_match('#(image|audio)#', $file['type']) && $this->strict)
		{
			$json=array();	
			$json['message'] = 'L\'extention n\'est pas prise en charge type: '.$file['type'];
			$json['error'] = true;
			
			die(json_encode($json)); 
		}*/




	$up = new Upload($_FILES['file'], false);
		if ($up->prepare())
		{
			//if (!$up->isWhitelisted($this->whiteList))
			//{
			//enregistrment du fichier dans le dossier:
			$up->save('media/'.$up->getMime());
			
			// Recupération du type de fichier - sans sous-type
			$typeFile = explode('/', $up->getMime()); 	$typeFile = $typeFile[0];
			$nameFile = trim(preg_replace( '#media/'.$up->getMime().'#','',$up->save_as), '/');
			
			// Enregistrment dans la base de donnée
			$media = $this->loadModel('Media');
			$id = $media->add($nameFile, $typeFile, $up->getMime(), $this->mvc->Session->user('id'));
			
			$json['html'] = $this->makeIt($id, $nameFile, $typeFile, $up->getMime());
			
			$json['error'] = false;
			
			die(json_encode($json)); 
			//} else { die('{"error":true, "message": "L\'extention n\'est pas prise en charge"}'); }
		} else { die('{"error":true, "message": "Echec de l\'upload !"}'); }
	} else { die('{"error":true, "message": "Fichier trop grand"}'); }
}



/**
 * Suppréssion du fichier via Javascript en Ajax 
 * @see Controller::ajax()
 */
public function ajax()
{
	if(isset($_GET['action']) && $_GET['action'] == 'delete')
	{
		if (isSet($_GET['id']))
		{
		$media = $this->loadModel('Media');
		
		$req = array('conditions' => array('id' => (int) $_GET['id']) );
		$match = $media->findFirst($req);
			if (!empty($match->mime))
			{
				if ($this->mvc->Session->user('id') == $match->id_member OR $this->mvc->Acl->isGrant())
				{
				unlink('media/'.$match->mime.'/'.$match->name);
				}
				else
				{
				die('{"error":true, "message": "Ce fichier, ne vous appartient pas"}'); 
				}
			}
		$media->delete($_GET['id']);
		} 
		die();
	}
}



}
<?php


Class mediaController extends Controller{
private $strict = false;
private $whiteList = array('.jpg', '.jpeg', '.mp3', '.gif', '.png', '.pdf', '.ogg', '.wav', '.sql');


public function index()
{

	if ($this->mvc->Acl->isAllowed())
	{

	
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
	else
	{
	Router::redirect('');
	}
		
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


public function ajax()
{
	if ($this->mvc->Acl->isAllowed())
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

		if (isSet($_FILES['file']))
		{

			$file = $_FILES['file'];
			$name = $file['name'];
			if(filesize($file['tmp_name']) > 10000000)
			{
				die('{"error":true, "message": "Image trop grande"}'); 
			}

			if(file_exists('media/'.$_FILES['file']['type'].'/'.$name))
			{
				die('{"error":true, "message": "Le fichier existe déjà"}'); 
			}

			if(!preg_match('#(image|audio)#', $file['type']) && $this->strict)
			{
				$json=array();	
				$json['message'] = 'L\'extention n\'est pas prise en charge type: '.$file['type'];
				$json['error'] = true;
				
				die(json_encode($json)); 
			}




		$up = new Upload($_FILES['file'], false);
		
			if ($up->controlExtWhiteList($this->whiteList))
			{
			//enregistrment du fichier
			$up->save('media/'.$_FILES['file']['type']);
			
			// Recupération du type de fichier - sans sous-type
			$typeFile = explode('/', $_FILES['file']['type']); 	$typeFile = $typeFile[0];
			$nameFile = trim(preg_replace( '#media/'.$_FILES['file']['type'].'#','',$up->save_as), '/');
			
			// Enregistrment dans la base de donnée
			$media = $this->loadModel('Media');
			$id = $media->add($nameFile, $typeFile, $_FILES['file']['type'], $this->mvc->Session->user('id'));
			
			$json['html'] = $this->makeIt($id, $nameFile, $typeFile, $_FILES['file']['type']);
			
			$json['error'] = false;
			
			die(json_encode($json)); 

			}
			else
			{
			die('{"error":true, "message": "L\'extention n\'est pas prise en charge"}'); 
			}
		//debug($up->log);
		}
	}
}



}
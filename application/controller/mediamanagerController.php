<?php

Class mediamanagerController extends Controller{
private $strict = false;
private $whiteList = array('jpg', 'jpeg', 'mp3', 'gif', 'png', 'pdf', 'ogg', 'wav', 'sql', 'zip', 'avi');


	public function index()
	{
	$acl = AccessControlList::getInstance();
	$page = Page::getInstance();
	$template = Template::getInstance();
	
		if (!$acl->isAllowed())
		{
			$c = $this->loadController('error');
			return $c->e403();
		}
	
	
		$page->setBreadcrumb('mediamanager', i18n::get('Media library'))
						->setPageTitle(i18n::get('Upload'));
		$mediaModel = new MediaModel();
		
		$find = array('limit' => '25', 'order' => 'id DESC');
	
		
		$template->listFiles = $mediaModel->find($find);
		$template->show('media/pupload');
	}


	public function browser()
	{
	$acl = AccessControlList::getInstance();
	$page = Page::getInstance();
	$request = Request::getInstance();
	$template = Template::getInstance();
	
		if (!$acl->isAllowed())
		{
			$c = $this->loadController('error');
			return $c->e403();
		}
		$page->setBreadcrumb('mediamanager', i18n::get('Media library'))
						->setPageTitle(i18n::get('Browser'));
		
		$mMedia = new MediaModel();
	
		if (!isSet($request->params['type']))
		{
			$prepare = array(
				'fields' => 'type , COUNT(type) AS countType',
				'order' => 'type ASC',
				'group' => 'type'
				);
			$template->group = $mMedia->find($prepare);
			
			$template->list = $mMedia->getList();
			$template->show('media/browser');
		}
		elseif(isSet($request->params['type']) && !isSet($request->params['sub']))
		{
			
			$type = $request->params['type'];
			$page->setBreadcrumb('mediamanager/browser', i18n::get('Browser'))
							->setBreadcrumb('mediamanager/browser/type:' . $type, $type);
			
			$prepare = array(
				'fields' => '* , CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType, COUNT(type) AS countSubType',
				'order' => 'subType ASC',
				'group' => 'subType',
				'conditions' => array('type' => $type)
				);
			$template->group = $mMedia->find($prepare);
			
			$template->list = $mMedia->getListByType($type);
			$template->show('media/browser-type');
		}
		elseif(isSet($request->params['type']) && isSet($request->params['sub']))
		{
			$type = $request->params['type'];
			$subType = $request->params['sub'];
			
			$page->setBreadcrumb('mediamanager/browser', i18n::get('Browser'))
							->setBreadcrumb('mediamanager/browser/type:' . $type, $type)
							->setBreadcrumb('mediamanager/browser/type:' . $type . '/sub:' . $subType, $subType);
			
			$prepare = array(
				'fields' => '* , CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType, COUNT(type) AS countSubType',
				'order' => 'subType ASC',
				'group' => 'subType',
				'conditions' => array('type' => $type)
				);
			$template->group = $mMedia->find($prepare);
			
			$template->list = $mMedia->getListBySubType($type.'/'.$subType);
			$template->show('media/browser-type-sub');
		}	
	}
	
	
	public function fileinfo()
	{
	$acl = AccessControlList::getInstance();
	$page = Page::getInstance();
	$request = Request::getInstance();
	$template = Template::getInstance();
	
		if (!$acl->isAllowed())
		{
			$c = $this->loadController('error');
			return $c->e403();
		}
		
		if (!isSet($request->params['id']))
		{
			return $this->browser();
		}
		
		$page->setPageTitle('Fileinfo')
				->setBreadcrumb('mediamanager', i18n::get('Media library'))
				->setBreadcrumb('mediamanager/browser', i18n::get('Browser'));
		
		$id = (int) $request->params['id'];
		
		$mMedia = new MediaModel();
		
		$fileinfo = $mMedia->getId($id);
		
		// Test si la requete abouti si pas on charge browser
		if (!$fileinfo)
		{
			return $this->browser();
		}
		// Recupère l'adresse du fichier
		$fileaddr = './media/upload/' . $fileinfo->folder . '/' . $fileinfo->name;
		
		
		$template->fileaddr = $fileaddr;
		$template->fileinfo = $fileinfo;
		$template->show('media/fileinfo');
	}
	
	
	public function edit()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$request = Request::getInstance();
		//$template = Template::getInstance();
		$session = Session::getInstance();
		$form = Form::getInstance();
		
		if (!$acl->isAllowed())
		{
			$c = $this->loadController('error');
			return $c->e403();
		}
		
		if (!isSet($request->params['id']))
		{
			return $this->browser();
		}
		
		
		
		$page->setPageTitle(i18n::get('Edition'));
		$page->setBreadcrumb('mediamanager', i18n::get('Media library'))
						->setBreadcrumb('mediamanager/browser', i18n::get('Browser'));
		
		$id = (int) $request->params['id'];
		
		$mMedia = new MediaModel();
		
		$fileinfo = $mMedia->getId($id);
		
		// Test si la requete abouti si pas on charge browser
		if (!$fileinfo)
		{
			return $this->browser();
		}
		
		switch($fileinfo->type)
		{
			case 'text':
				$dl = null;
				$fn = './media/upload/'. $fileinfo->folder . '/'.$fileinfo->name;
	
				if (isset($request->data->content))
				{
					$content = clean($_POST['content'], 'html');
					file_put_contents($fn, $content);
					$session->setFlash(i18n::get('Saved file'));
					
					if (isSet($request->data->name))
					{
						if ($fileinfo->name != $request->data->name)
						{
							if (strrpos( $request->data->name , '.'))
							{
								$ext = substr( $request->data->name , strrpos( $request->data->name , '.') + 1);
								$sublenght = strlen($ext);
								// On ajoute 1 pour simulé le point
								$sublenght++;
								// On cherche la somme négative
								$sublenght = $sublenght - ($sublenght * 2);
								// On retire l'extention
								$request->data->name = substr($request->data->name, 0, $sublenght);
								
								// On nettoye les chaines
								$request->data->name = clean($request->data->name, 'slug');
								$ext = clean($ext, 'slug');
								
								if (strlen($ext) > 0)
								{
									$request->data->name .= '.' . $ext; 
								}
								
								
							} else {$request->data->name = clean($request->data->name, 'slug');}
							$fnOld = $fn;
							$fn = './media/upload/'. $fileinfo->folder . '/'.$request->data->name;
							rename($fnOld, $fn);
							
							$file = new stdClass();
							$file->name = $request->data->name;
							$file->id = $id;
							$mMedia->save($file);
						}
					}
					
				}
				else
				{
					$dl = file_get_contents($fn);
				}
				
				if ($fileinfo->mime == 'text/html')
				{
					echo '<form  method="post">' . $form->input('name', i18n::get('File name'), array('value' => $fileinfo->name)) .
					$form->input('content', i18n::get('File content'), array('type' => 'textarea', 'editor' => 'html', 'value' => $dl)) .
					$form->input('submit', i18n::get('Save'), array('type' => 'submit')) . '</form>';
				}
				else
				{
					echo '<form  method="post">' . $form->input('name', i18n::get('File name'), array('value' => $fileinfo->name)) . 
					$form->input('content', i18n::get('File content'), array('type' => 'textarea', 'value' => $dl, 'style'=> 'width:100%;height:450px;')) . 
					$form->input('submit', i18n::get('Save'), array('type' => 'submit')) . '</form>';
				}
				
				return false;
			break;
			case 'image':
				
				/*
				$this->mvc->Template->urlImage = 'media/upload/'. $fileinfo->folder . '/'.$fileinfo->name;
				$this->mvc->Template->show('media/image/editor');*/
				$session->setFlash(i18n::get('The file can not be modified at this time', $fileinfo->type));
				return Router::redirect('mediamanager/fileinfo/id:' . $id); 
			break;
			default:
				$session->setFlash(i18n::get('The file can not be modified at this time', $fileinfo->type));
				return Router::redirect('mediamanager/fileinfo/id:' . $id);
			break;
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
		list($width, $height, $type, $attr) = getimagesize(__CW_PATH . '/media/upload/'.date('Y/m/d').'/' . $name);
		
			if (preg_match('#gif#', $name))
			{
			?>
			<a href="<?php echo  __CW_PATH . '/media/upload/'.date('Y/m/d').'/' . $name; ?>" onclick="window.open(this.href, 'pop<?php echo $id;?>','menubar=no, status=no, scrollbars=yes, width=<?php echo $width;?>, height=<?php echo $height;?>');return false;"><img src="<?php echo  __CW_PATH . '/media/upload/'.date('Y/m/d').'/' . $name; ?>" alt="" /></a>
			<?php
			}
			elseif (preg_match('#(png|jpe?g)#', $name))
			{
			?>
			<a href="<?php echo  __CW_PATH . '/media/upload/'.date('Y/m/d').'/' . $name; ?>" onclick="window.open(this.href, 'pop<?php echo $id;?>','menubar=no, status=no, scrollbars=yes, width=<?php echo $width;?>, height=<?php echo $height;?>');return false;"><img src="<?php echo  __CW_PATH . '/media.php?thumb=/upload/'.date('Y/m/d').'/' . $name; ?>" alt="" /></a>
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
	  <source src="' . __CW_PATH . '/media/upload/'.date('Y/m/d'). '/' . $name . '" type="'.$mime.'" />
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
				<input type="text" value="<?php echo __CW_PATH . '/media/upload/'.date('Y/m/d') . '/' . $name; ?>" >
				<a href="<?php echo $id; ?>" class="btn del"><?php echo i18n::get('Delete'); ?></a>
			</div>
		</div>
	</div>
	</form>
	<?php
	return ob_get_clean();
	}
	
	/**
	 * Enregistre le fichier uploader
	 */
	private function addfile()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		
		if (!$acl->isAllowed())
		{
			die('{"error":true, "message": "Droit d\'envois requis"}'); 
		}
		
		$page->setLayout('empty');
		if (isSet($_FILES['file']))
		{
			if(filesize($_FILES['file']['tmp_name']) > _get_bytes(ini_get('upload_max_filesize')))
			{
				die('{"error":true, "message": "Image trop grande a'.filesize($_FILES['file']['tmp_name']).' b' ._get_bytes(ini_get('upload_max_filesize')) . '"}');
			}
			
		$up = new Upload($_FILES['file'], false);
			if ($up->prepare())
			{
				//if (!$up->isWhitelisted($this->whiteList))
				//{
				//enregistrment du fichier dans le dossier:
				$up->setDirToSave('media/upload/'.date('Y/m/d'));
				
				// Recupération du type de fichier - sans sous-type
				/**/
				$typeFile = explode('/', $up->getMime());
				$typeFile = $typeFile[0];
				$nameFile = trim(preg_replace( '#media/upload/'.date('Y/m/d').'#','',$up->save_as), '/');
	
				
				// Enregistrment dans la base de donnée
				$media = new MediaModel();
				$id = $media->add($nameFile, $up->getMime(), $session->user('id'), date('Y/m/d'));
				
				$json['html'] = $this->makeIt($id, $nameFile, $typeFile, $up->getMime());
				
				$json['error'] = false;
				
				die(json_encode($json)); 
				//} else { die('{"error":true, "message": "L\'extention n\'est pas prise en charge"}'); }
			} else { die('{"error":true, "message": "Echec de l\'upload !"}'); }
		} else { die('{"error":true, "message": "Fichier trop grand"}'); }
	}
	
	
	
	public function rpc()
	{
		$acl = AccessControlList::getInstance();
		$session = Session::getInstance();
		
		if (!$acl->isAllowed())
		{
			die('{"error":true, "message": "Droit requis"}'); 
		}
		
		if(isset($_GET['action']))
		{
			switch($_GET['action'])
			{
				case 'delete':
					if (isSet($_GET['id']))
					{
						$media = new MediaModel();
					
						$req = array('conditions' => array('id' => (int) $_GET['id']) );
						$match = $media->findFirst($req);
						if (!empty($match->mime))
						{
							if ($session->user('id') == $match->id_member OR $acl->isGrant())
							{
								$media->delete($_GET['id']);
								if (unlink('media/upload/'.$match->folder.'/'.$match->name))
								{
									die('{"error":false, "message": "Success del media/'.$match->mime.'/'.$match->name .'"}');
								} else {
									die('{"error":false, "message": "Faild  to delete file media/'.$match->mime.'/'.$match->name .'"}');
								}
								
							}
							else
							{
								die('{"error":true, "message": "Ce fichier, ne vous appartient pas"}');
							}
						}
						
					}
				break;
				
				case 'addfile':
					$this->addfile();
					
					
					die;
				break;
				
			}
				
		}
		
		
		die('{"error":true, "message": "'.i18n::get('Missing parameter').'"}');
	}

	
	public function plupload()
	{
		$acl = AccessControlList::getInstance();
		
		if (!$acl->isAllowed())
		{
			$c = $this->loadController('error');
			return $c->e403();
		}
	header("Content-type: text/javascript");
	?>
		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash',
			containes: 'plupload',
			browse_button: 'browse',
			drop_element:"droparea",
			url : '<?php echo Router::url('mediamanager/rpc'); ?>?action=addfile',
			flash_swf_url:'./files/js/plupload/plupload.flash.swf',
			multipart : true,
			urlstream_upload:true,
			max_file_size:'<?php echo (ini_get('upload_max_filesize')); ?>',
			});
			
		
		uploader.bind('Init',function(up, params){
			if(params.runtime != 'html5'){
				$('#droparea').css('border','none').find('p,span').remove();
			} 
		})
		
		uploader.bind('UploadProgress',function(up, file){
			console.log(file);
			$('#'+file.id).find('.bar').css('width',file.percent+'%').attr("title", file.percent+'%');
		})
		
		uploader.init();
		
		uploader.bind('FilesAdded',function(up,files){
			console.log(files);
			var filelist = $('#filelist');
			for(var i in files){
				var file = files[i]; 
				filelist.prepend('<div id="'+file.id+'" class="file">'+file.name+' ('+plupload.formatSize(file.size)+')'+'<div class="progress progress-success progress-striped active" style="position: absolute;top: 25px;right: 5px;width: 150px;height: 20px;"><div class="bar"></div></div></div>');
			}
		
			$('#droparea').removeClass('hover')
			uploader.start();
			uploader.refresh();
		});
		
		
		uploader.bind('Error',function(up, err){
				console.log(err);
				alert(err.message);
				$('#droparea').removeClass('hover')
				uploader.refresh();
		});
		
		uploader.bind('FileUploaded',function(up, file, response){
		
		console.log(file);
		console.log(response);
			data = $.parseJSON(response.response);
			if(data.error){
				alert(data.message); 
				$('#'+file.id).remove(); 
			}else{
				$('#'+file.id).replaceWith(data.html); 
			}
		});
		
		
			
		jQuery(function($){
			$('#droparea').bind({
			   dragover : function(e){
			
			   $(this).addClass('hover'); 
			   },
			   dragleave : function(e){
			
			   $(this).removeClass('hover'); 
			   }
			});
			
			$('.del').live('click',function(e){
			e.preventDefault();
			
			var elem = $(this); 
				if(confirm('<?php echo i18n::get('Do you really want delete this file?'); ?>')){
				$.getJSON('<?php echo Router::url('mediamanager/rpc'); ?>',
						{action:'delete',id:elem.attr('href')},
						function(data){
		     				if (!data.error)
		     				{
		     					elem.parent().parent().parent().slideUp(); 
		     					console.log('PlUpload: ' + data.message);
		     				} else {
		     					alert(data.message);
		     				}
		   				});
				console.log('PlUpload: <?php echo Router::url('mediamanager/rpc'); ?>?action=delete&id=' + elem.attr('href'));

				}
			return false; 
			});
		})
		<?php 
		die;
	}


}
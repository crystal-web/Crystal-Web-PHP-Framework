<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}


Class faqController extends Controller {

public function index()
{
	$this->mvc->Page->setPageTitle('Foire aux questions')
					->setHeader('
	<script type="text/javascript">
	jQuery(function($){
		/***************************************
		*	FAQ
		***************************************/
		$("#faq h5").click(function(){
			var me = $(\'#reponse_\' + $(this).attr("id"));
			if (me.is(\':visible\'))
			{
			me.slideUp();
			}
			else
			{
			me.slideDown();
			}	
		});
		if ($("#faq").size() > 0)
		{
		$("#faq .faq").hide();
		}
	});
	</script>
	');
	
	
	$faq = loadModel('Faq');
	
	$this->mvc->Template->faq = $faq->find(array('conditions' => array('active' => 'on')));
	$this->mvc->Template->show('faq/index');
}

public function manager()
{
	if ($this->mvc->Acl->isAllowed())
	{
		$faq = loadModel('Faq');
	
		if (isSet($this->mvc->Request->data->question) && $this->mvc->Session->token())
		{	
			$this->mvc->Session->makeToken();
			$faq->save($this->mvc->Request->data);
			Router::redirect('faq');	
		}
	
		if (isSet($this->mvc->Request->params['id']) && $this->mvc->Session->token())
		{
			$this->mvc->Session->makeToken();
			
			$data = new stdClass();
			$data->id = $this->mvc->Request->params['id'];
			$data->active = 'off';
			$faq->save($data);
			Router::redirect('faq');
		
		}
		else
		{
			return $this->index();
		}
	}
	else
	{
		return $this->index();
	}
}

}
?>
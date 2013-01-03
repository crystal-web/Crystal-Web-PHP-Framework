<?php
$page = Page::getInstance();
$acl = AccessControlList::getInstance();
$session = Session::getInstance();
$form = Form::getInstance();

$page->setHeader(
"
<script type=\"text/javascript\">
function delFaq(postHref)
{
document.getElementById(\"del\").href = postHref;
$('#modalTitle').text('Alerte...');
$('#modalText').html('<p>Supprimer la question ?</p>');
}
</script>"
);

?>
<div id="delfaq" class="modal hide fade">
	<div class="modal-header">
		<a href="#" class="close">&times;</a>
		<h3 id="modalTitle"></h3>
	</div>
	
	<div class="modal-body" id="modalText"></div>
	<div class="modal-footer">
		<a href="#" class="btn" onclick="$('#delfaq').modal('hide');">NON</a>
		<a href="#" id="del" class="btn danger">OUI</a>
	</div>
</div>
<div id="faq" class="well">

<?php
$tok=null;
if ($acl->isAllowed('faq','manager')){
?>
<form method="post" action="<?php echo Router::url('faq/manager'); ?>?token=<?php echo $session->getToken(); ?>">
<?php

echo $form->input('question', 'Question').
$form->input('reponse', 'Réponse', array('type' => 'textarea', 'editor' => '')).
$form->input('submit', 'Ajouter', array('type' => 'submit', 'class' => 'btn primary'));

?>
</form>
<?php
$tok = $session->getToken();
}
	if (count($faq)):
	
	loadFunction('bbcode');
	
	foreach($faq AS $k => $v) : 
	
	?>
	<h5 id="quest_<?php echo $v->id; ?>">Q : <?php echo clean($v->question, 'str'); ?>
	
	<?php 
	if ($acl->isAllowed('faq', 'manager')){
		echo '<span><a href="#" title="Supprimer la question" class="toolTip" data-controls-modal="delfaq" data-backdrop="true" data-keyboard="true" onclick="delFaq(\''.Router::url('faq/manager/id:'.$v->id).'?token='.$tok.'\');"><img src="'.__CDN.'/files/images/icons/status.png"></a></span>';
	}
	?>
	</h5>
	<div class="faq" id="reponse_quest_<?php echo $v->id; ?>">R : 
<?php echo clean($v->reponse, 'bbcode'); ?>
	</div>
<?php endforeach;
	else:
	$session->setFlash('Pas encore de réponse dans la FAQ');
	endif;
?>
</div>
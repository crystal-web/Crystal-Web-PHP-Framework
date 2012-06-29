<div id="faq" class="well">

<?php
$tok=null;
if ($this->mvc->Acl->isAllowed('faq.manager')){
?>
<form method="post" action="<?php echo Router::url('faq/manager'); ?>?token=<?php echo $this->mvc->Session->getToken(); ?>">
<?php

echo $this->mvc->Form->input('question', 'Question').
$this->mvc->Form->input('reponse', 'Réponse', array('type' => 'textarea', 'editor' => '')).
$this->mvc->Form->input('submit', 'Ajouter', array('type' => 'submit', 'class' => 'btn primary'));

?>
</form>
<?php
$tok = $this->mvc->Session->getToken();
}
	if (count($faq)):
	
	loadFunction('bbcode');
	
	foreach($faq AS $k => $v) : 
	
	?>
	<h5 id="quest_<?php echo $v->id; ?>">Q : <?php echo clean($v->question, 'str'); ?>
	
	<?php 
	if ($this->mvc->Acl->isAllowed('faq', 'manager')){
		echo '<span><a href="'.Router::url('faq/manager/id:'.$v->id).'?token='.$tok.'" title="Supprimer la question" class="toolTip"><img src="'.__CDN.'/files/images/icons/status.png"></a></span>';
	}
	?>
	</h5>
	<div class="faq" id="reponse_quest_<?php echo $v->id; ?>">R : 
<?php echo clean($v->reponse, 'bbcode'); ?>
	</div>
<?php endforeach;
	else:
	$this->mvc->Session->setFlash('Pas encore de réponse dans la FAQ');
	endif;
?>
</div>
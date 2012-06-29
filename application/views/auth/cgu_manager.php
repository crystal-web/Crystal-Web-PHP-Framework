<?php 
//echo $cgu;

echo '<form method="post">'.$this->mvc->Form->input('title', 'Titre: ', array('value' => $cgu->title)).
$this->mvc->Form->input('text', 'Texte: ', 
	array('type' => 'textarea',
		'editor' => array(
			'params' => array('model' => 'htmlfull')
			),
		'value' => $cgu->text)).
$this->mvc->Form->input('submit', 'Enregistrer ', array('type' => 'submit', 'class' => 'btn success')).
'</form>';
?>

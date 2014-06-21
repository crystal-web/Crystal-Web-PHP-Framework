<?php 
//echo $cgu;
$form = Form::getInstance();

echo '<form method="post" class="form-horizontal">'.$form->input('title', 'Titre: ', array('value' => $cgu->title)).
$form->input('text', 'Texte: ', 
	array('type' => 'textarea',
		'editor' => array(
			'params' => array('model' => 'htmlfull')
			),
		'value' => $cgu->text)).
$form->input('submit', 'Enregistrer ', array('type' => 'submit', 'class' => 'btn success')).
'</form>';
?>

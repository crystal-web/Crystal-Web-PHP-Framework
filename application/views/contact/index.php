<div class="well">
<fieldset>
	<legend>Formulaire de contact</legend>
	<form method="post">
	<?php
	$form = Form::getInstance();
	echo $form->input('firstname', 'Prénom').
	$form->input('lastname', 'Nom').
	$form->input('mail', 'Adresse e-mail').
	$form->input('motif', 'Objet').
	$form->input('message', 'Votre mesage', array('type' => 'textarea', 'editor' => 'bbcode')).
	'<div class="clearfix">
		<label for="captcha">Captcha : </label>
		<div class="input">
			' . $captcha_img.$captcha_hidden.$captcha_input . '
			<span class="help-block">Clique pour changé les couleurs</span>
		</div>
	</div>'.
	$form->input('submit', 'Envoyer', array('type' => 'submit', 'class' => 'btn primary' ));
	?>
	
	</form>
</fieldset>
</div>
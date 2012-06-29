<div class="well">
<fieldset>
	<legend>Formulaire de contact</legend>
	<form method="post">
	<?php
	echo $this->mvc->Form->input('firstname', 'Prénom').
	$this->mvc->Form->input('lastname', 'Nom').
	$this->mvc->Form->input('mail', 'Adresse e-mail').
	$this->mvc->Form->input('motif', 'Objet').
	$this->mvc->Form->input('message', 'Votre mesage', array('type' => 'textarea', 'editor' => '')).
	'<div class="clearfix">
		<label for="captcha">Captcha : </label>
		<div class="input">
			' . $captcha_img.$captcha_hidden.$captcha_input . '
			<span class="help-block">Clique pour changé les couleurs</span>
		</div>
	</div>'.
	$this->mvc->Form->input('submit', 'Envoyer', array('type' => 'submit', 'class' => 'btn primary' ));
	?>
	
	</form>
</fieldset>
</div>
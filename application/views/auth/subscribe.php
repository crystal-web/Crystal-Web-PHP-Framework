<form method="post">

<fieldset>
	<legend>Formulaire d'inscription</legend>
	<?php
	echo $this->mvc->Form->input('loginmember', 'Pseudo: ').
	$this->mvc->Form->input('passmember', 'Mot de passe: ', array('type'=>'password')).
	$this->mvc->Form->input('otherpassword', 'Mot de passe: ', array('help' => 'Confirmation','type'=>'password')).
	$this->mvc->Form->input('mailmember', 'E-mail: ', array('addon' => '@'));
	?>
	<div class="clearfix">
		<label for="captcha">Captcha : </label>
		<div class="input">
			<?php echo $captcha_img.$captcha_hidden.$captcha_input; ?>
			<span class="help-block">Clique pour changé les couleurs</span>
		</div>
	</div>		

	<?php
	echo $this->mvc->Form->input('declare_coche', '', array(
		'type' => 'checkbox',
		'option' => array('declare_coche' => '<a href="'.Router::url('auth/cgu').'">J\'ai lu et j\'accepte le réglement</a>')));
	?>
	<div class="clearfix">
		<div class="input">
			<input type="submit" name="submit" value="Connection" class="btn success">
		</div>
	</div>
</fieldset>

</form> 

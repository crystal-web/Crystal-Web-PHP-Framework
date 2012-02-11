<?php

if (count($errors_registration) && !empty($errors_registration))
{
$err=NULL;
	foreach ($errors_registration AS $value)
	{
	$err .= '<li>'.$value.'</li>';
	}
echo'<div class="alert-message block-message error fade in" data-alert="alert" >
	<a class="close" href="#">&times;</a>
	<p>Une ou plusieurs erreurs se sont  produites lors de votre inscription.
		<ul>
			'.$err.'
		</ul>
	</p>
</div>';
}
?>
<form method="post">

<fieldset>
	<legend>Formulaire d'inscription</legend>
	<div class="clearfix">
		<label>Pseudo: </label>
		<div class="input">
			<input type="text" name="user" value="<?php echo $login;?>" placeholder="Pseudo">
		</div>
	</div>
	<div class="clearfix">
		<label>Mot de passe: </label>
		<div class="input">
			<input type="password" name="password" placeholder="Password">
		</div>
	</div>
	<div class="clearfix">
		<label>Mot de passe: </label>
		<div class="input">
			<input type="password" name="otherpassword" placeholder="Password">
			<span class="help-block">Confirmation</span>
		</div>
	</div>
	


              
	<div class="clearfix">
		<label>E-mail: </label>
		<div class="input-prepend">
			<div class="input">
				<span class="add-on">@</span>
				<input type="text" name="mail" value="<?php echo $email;?>" placeholder="anne@ony.me">
			</div>
		</div>
	</div>
	<div class="clearfix">
		<label for="captcha">Captcha : </label>
		<div class="input">
			<?php echo $captcha_img.$captcha_hidden.$captcha_input; ?>
		</div>
	</div>		
	
	
	<div class="clearfix">
		<div class="input">
<textarea cols="200" rows="15" style="font-weight:700; color:#5B6CBA;">
<?php echo __CGU; ?>
</textarea>
		</div>
	</div>	
	<div class="clearfix">
		<label for="declare"></label>
		<div class="input">
			<input type="checkbox" name="declare_coche" value="on" id="declare">
			<span>J'ai lu et j'accepte les conditions générales d'utilisation</span>
		</div>
	</div>
	
	<div class="clearfix">
		<div class="input">
			<input type="submit" name="submit" value="Connection" class="btn success">
		</div>
	</div>
</fieldset>

</form> 

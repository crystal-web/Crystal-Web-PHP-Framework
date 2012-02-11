<?php
if (isSet($errors_connection))
{
	if (count($errors_connection))
	{
		?>
		<div class="alert-message block-message error fade in" data-alert="alert" >
			<a class="close" href="#">&times;</a>
			<p><?php foreach ($errors_connection AS $v){echo $v;} ?></p>
			<div class="alert-actions">
				<a class="btn small" href="<?php echo url('index.php?module=auth&action=forgotpassword'); ?>">Vous avez perdu votre mot de passe ?</a>
				<a class="btn small" href="<?php echo url('index.php?module=auth&action=subscribe'); ?>">Pas encore de compte ?</a>
			</div>
		</div>
		<?php
	}
}

?>
<form action="<?php echo url('index.php?module=auth'); ?>" method="post">
<fieldset>
	<legend>Authentification</legend>
	<div class="clearfix">
		<label>Pseudo: </label>
		<div class="input">
			<input type="text" name="user" placeholder="Pseudo">
		</div>
	</div>
	<div class="clearfix">
		<label>Mot de passe: </label>
		<div class="input">
			<input type="password" name="password" placeholder="Password">
		</div>
	</div>
	<div class="clearfix">
		<label>
		Connection auto: </label>
		<div class="input">
			<input type="checkbox" name="password">
		</div>
	</div>
	<div class="clearfix">
		<div class="input">
			<input type="submit" name="submit" value="Connection" class="btn success">
		</div>
	</div>
</fieldset>
</form>
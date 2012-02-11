<?php
if (isSet($errors_forgotpassword))
{
	if (count($errors_forgotpassword))
	{
		?>
		<div class="alert-message block-message error fade in" data-alert="alert" >
			<a class="close" href="#">&times;</a>
			<p><?php foreach ($errors_forgotpassword AS $v){echo $v;} ?></p>
			<div class="alert-actions">
				<a class="btn small" href="<?php echo url('index.php?module=auth&action=subscribe'); ?>">Pas encore de compte ?</a>
			</div>
		</div>
		<?php
	}
}

?>
<form action="<?php echo url('index.php?module=auth&action=forgotpassword'); ?>" method="post">
<fieldset>
	<legend>Retrouver mes identifiants</legend>
	
	<div class="clearfix">
		<label for="web">E-mail: </label>
		<div class="input">
			<div class="input-prepend">
				<span class="add-on">@</span>
				<input type="text"name="forgot" placeholder="moi@domain.com">
			</div>
		</div>
	</div>
						
	<div class="clearfix">
		<div class="input">
			<input type="submit" name="submit" value="Connection" class="btn success">
		</div>
	</div>
</fieldset>
</form>
<form method="post">
<div class="widget">
	<div class="widget-content">
<?php 
$form = Form::getInstance();
echo $form->input('passmember', i18n::get('Your password'), array('type' => 'password'));
echo $form->input('passmember2', i18n::get('Your password again'), array('type' => 'password'));
?>
<div class="actions">
<input type="submit" id="inputsend" value="<?php echo i18n::get('Save my password'); ?>" class="btn primary" onclick="this.disabled=1; this.form.submit();">
</div>
	</div>
</div>

</form>
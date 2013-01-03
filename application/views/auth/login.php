<form action="<?php echo Router::url('auth'); ?>" method="post">

<div class="widget">
	<div class="widget-content">
<?php
$form = Form::getInstance();
echo $form->input('loginmember', i18n::get('Your login'));
echo $form->input('passmember', i18n::get('Your password'), array('type' => 'password'));
echo $form->input('connect', '', array('type' => 'checkbox', 'option' => array(i18n::get('Remember me'))));
?>
<div class="actions">
<input type="submit" id="inputsend" value="<?php echo i18n::get('Connection'); ?>" class="btn primary" onclick="this.disabled=1; this.form.submit();">
<input type="button" id="inputforgot" value="<?php echo i18n::get('Forgot password'); ?>" class="btn" onclick="this.disabled=1; document.location.href='<?php echo Router::url('auth/forgotpassword'); ?>';">
</div>
	</div>
</div>

</form>
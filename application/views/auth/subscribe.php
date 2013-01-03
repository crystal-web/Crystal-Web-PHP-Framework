<form method="post">

<div class="widget">
	<div class="widget-header"><h3>Formulaire d'inscription</h3></div>	
	<div class="widget-content">
	<?php
	$form = Form::getInstance();
	echo $form->input('loginmember', i18n::get('Your login')).
	//$this->mvc->Form->input('passmember', i18n::get('Your password'), array('type'=>'password')).
	//$this->mvc->Form->input('otherpassword', i18n::get('Your password again'), array('type'=>'password')).
	$form->input('mailmember', i18n::get('Your address email'), array('addon' => '@')) . 
	$form->input('othermail', i18n::get('Your address email again'), array('addon' => '@'));
	?>
	
	<div class="clearfix">
		<label for="captcha"><span style="float: right;margin: -6px;"><?php echo Captcha::generateImgTags(".."); ?></span></label>
		<div class="input">
		    <span>
			<?php echo Captcha::generateHiddenTags().Captcha::generateInputTags(); ?>
			</span>
			<span class="help-block"><?php echo i18n::get('Click the picture to change the color'); ?></span>
		</div>
	</div>
	
	
	<div class="clearfix">
		<div class="input">
			<input type="submit" name="submit" value="Inscription" class="btn success">
		</div>
	</div>
	
	
	</div>
</div>



</form> 

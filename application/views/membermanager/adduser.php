<?php 
require 'tabs.inc.php';
?>
	<div class="widget">
		<div class="widget-header">
			<h3>Cr&eacute;ation d'un compte</h3>
		</div>
		<div class="widget-content">
<form method="post">
<?php
$form = Form::getInstance();
$acl = AccessControlList::getInstance();

	echo 
		$form->input('login', i18n::get('Login'), 
			array(
				'autocomplete' => "off",
				)
			) . 
		$form->input('mail', i18n::get('Mail'), 
			array(
				'autocomplete' => "off",
				)
			) . 
		$form->input('password', i18n::get('Password'), array('type' => 'password', 'help' => i18n::get('If empty, the password has generat and send by mail'))) .
		$form->input('passwordagain', i18n::get('Password again'), array('type' => 'password'));
		
		if ($acl->isAllowed('membermanager', 'changegroup'))
		{
			echo $form->input('group', i18n::get('Group'), 
				array(
					'type' => 'select', 
					'option' => $groupList
					)
				);
		}
		
		echo $form->input('submit', i18n::get('Add user'), array('type' => 'submit', 'class' => 'btn success'))
		;
?>
</form>

	</div>
</div>
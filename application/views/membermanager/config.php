<?php 
require 'tabs.inc.php';
?>
<form method="post">
	<div class="widget">
		<div class="widget-header">
			<h3>Configuration des membres</h3>
		</div>
		<div class="widget-content">
<?php
$form = Form::getInstance();
	echo 
		$form->input('activMode', 'Mode d\'activation du compte membre:', 
			array(
				'type' => 'select',
				'option' => array(
					'auto' => 'Validation automatique',
					'mail' => 'Validation par mail',
					),
				'value' => $config->activMode
				)
			)
		;
?>
		</div>
	</div>
	
	

	<div class="widget">
		<div class="widget-header">
			<h3>Condition g&eacute;n&eacute;ral d'utilisation</h3>
		</div>
		<div class="widget-content">
<?php
echo
	$form->input('cgutitle', 'Titre: ', array('value' => clean($config->cgutitle, 'str'))).
	$form->input('cgutext', 'Texte: ', 
		array('type' => 'textarea',
			'editor' => array(
				'params' => array('model' => 'htmlfull')
				),
			'value' => clean($config->cgutext, 'str')))
		;
?>
		</div>
	</div>

	
<?php echo $form->input('submit', 'Enregistrer ', array('type' => 'submit', 'class' => 'btn success')); ?>
</form>


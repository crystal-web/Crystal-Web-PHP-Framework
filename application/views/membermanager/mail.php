<?php 
require 'tabs.inc.php';
$form = Form::getInstance();
?>
<form method="post">
	<div class="widget">
		<div class="widget-header">
			<h3>Envoie de mail</h3>
		</div>
		<div class="widget-content">
<?php
	echo 
		$form->input('from', 'De:', array('placeholder' => 'site@mail.ltd') ) . 
		$form->input('to', 'A:', array('value' => $member->mailmember, 'placeholder' => $member->mailmember) ) .
		$form->input('object', 'Objet:' ) .
		$form->input('message', 'Message:', array('type' => 'textarea', 'editor' => 'html', 'value' => '<h4>Bonjour ' . $member->loginmember. '</h4>') ) .
 		$form->input('submit', 'Envoie', array('type' => 'submit') ) 
		;
?>
		</div>
	</div>	
</form>
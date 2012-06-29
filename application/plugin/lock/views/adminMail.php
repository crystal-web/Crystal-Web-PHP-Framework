<?php
$nb = count($mailList);
$s = ($nb > 1) ? 's' : '';

echo $nb . ' enregistré'.$s . ' dont '.count($adminMail).' aillant accès<br>';
 
	echo '<ul>';
		foreach($adminMail AS $k => $v):
			echo '<li>' . $v . '</li>';
		endforeach;
	echo '</ul>';



echo '<form method="post">' . $this->mvc->Form->input('mail', 'E-mail aillant accès:').
$this->mvc->Form->input('submit', 'Ajouter l\'adresse aillant accès', array('type' => 'submit'))
. '</form>';
?>

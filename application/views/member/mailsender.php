<div style="margin:10px;">
<?php 
$s = ($nbMail > 1) ? 's' : '';
echo ' ' . $nbMail . ' adresse'.$s.' e-mail enregistrée' . $s.'<br>';

$tempsPourEnvois = round(( $nbMail / $nbMailParPage ) * $whait, '2');
$se = ($tempsPourEnvois  > 1) ? 's' : '';
echo ' Envois de'.$s.' e-mail'.$s.' possible en ' . $tempsPourEnvois . ' seconde' . $se . ' environt.';
?>
</div>
<form method="post">
<?php 

echo	$this->mvc->Form->input('title', 'Titre: ').
		$this->mvc->Form->input('content', 'Message: ', array('type' => 'textarea',
																'editor' => array()
																)).
		$this->mvc->Form->input('submit', 'Envoyer', array('type'=>'submit'));
?>

</form>
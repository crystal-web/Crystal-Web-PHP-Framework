<?php
	$this->mvc->Page->setHeader("
		<style>
		ul.inline li { 
		display : inline;
		padding : 0 0.5em;
		}
		ul.inline {
		list-style-type : none;
		}
		</style>
	");
	
	echo '<ul class="inline">
		<li><a href="' . Router::url('member/edit') . '">Modifier mon profil</a></li>
		<li><a href="' . Router::url('member/change_password') . '">Changement de mot de passe</a></li>
		<li><a href="' . Router::url('member/change_login') . '">Changement de pseudo</a></li>
	</ul>';
?>
<div class="well">
	<p>
Il est possible de changer de pseudo, mais cela ne doit pas &ecirc;tre fait &agrave; la l&eacute;g&egrave;re.<br>
Un administrateur doit valider votre changement de pseudo et il peut le refuser si vous en changez trop r&eacute;guli&egrave;rement.<br>
Dans la mesure du possible, &eacute;vitez de faire des changements de pseudo car cela porte &agrave; confusion pour tout le monde.<br>
	</p>
</div>

<form method="post">
<?php echo $form; ?>
</form>
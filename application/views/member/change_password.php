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
Vous désirez changer de mot de passe ?<br>
Pour des raisons de sécurité, vous devez d'abord indiquer le mot de passe actuel.<br>
Tapez ensuite 2 fois le nouveau mot de passe que vous désirez utiliser.
</p>
<p>Utilisez de préférence un mot de passe assez long mélangeant lettres et chiffres.<br>
Évitez à tout prix les mots de passe trop "évidents" comme votre prénom, votre pseudo ou votre date de naissance...</p>
</div>
<form method="post">
<?php echo $form; ?>
</form>
<table width="90%" class="table_style">
<tr><th colspan="2">Recherche d'un membre</th></tr>
<tr><td>Pseudo du membre<br />
Utilise aussi le joker (*)</td><td><form method="post" action="index.php?module=admin_member">
<input type="text" name="l_member" />
<input type="submit" name="l_search" value="Rechercher" />
</form>
</td></tr>
</table>

<br />

<table width="90%" class="table_style">
<tr><td>

	<table width="90%">
	<tr>
	<th onclick="window.open('index.php?module=admin_member<?php echo $i; ?>','_self');">ID</th>
	<th onclick="window.open('index.php?module=admin_member&o=member<?php echo $i; ?>','_self');">Login</th>
	<th onclick="window.open('index.php?module=admin_member&o=mail<?php echo $i; ?>','_self');">Adresse e-mail</th>
	<th onclick="window.open('index.php?module=admin_member&o=firstactivity<?php echo $i; ?>','_self');">Date d'inscription</th>
	<th onclick="window.open('index.php?module=admin_member&o=lastactivity<?php echo $i; ?>','_self');">Dérniere connection</th>
	<th>Action</th>
	</tr>
	<?php 
	if (!empty($thismember))
	{
	echo '<tr style="background-color:#c0c0c0;border-style:solid;border-color:red;">
		<td>' . $thismember['idmember'] . '</td>
		<td>' . $thismember['loginmember'] . '</td>
		<td>' . $thismember['mailmember'] . '</td>
		<td>' . date('d-m-Y H:i',$thismember['firstactivitymember']) . '</td>
		<td>' . date('d-m-Y H:i',$thismember['lastactivitymember']) . '</td>

		<td>Action</td>
		</tr>';
	}

		foreach ($listmember as $key => $data){
		echo '<tr>
		<td>' . $data['idmember'] . '</td>
		<td>' . $data['loginmember'] . '</td>
		<td>' . $data['mailmember'] . '</td>
		<td title="' . getRelativeTime($data['firstactivitymember']) . '">' . date('d-m-Y H:i',$data['firstactivitymember']) . '</td>
		<td title="' . getRelativeTime($data['lastactivitymember']) . '">' . date('d-m-Y H:i',$data['lastactivitymember']) . '</td>

		<td>Action</td>
		</tr>';
		}

	?>
	</table>

</td></tr>
<tr><td>

<p align="center">
<?php 
$searchInThis = (isSet($_GET['idOffre'])) ? '&idOffre=' . $_GET['idOffre'] : NULL;
$thispage = (isSet($_GET['page'])) ? (int) $_GET['page'] : 1;

echo '<p align="center">Page : '; //Pour l'affichage, on centre la liste des pages
for($i=1; $i<=$nombreDePages; $i++) //On fait notre boucle
{
	//On va faire notre condition
	if($i==$thispage) //Si il s'agit de la page actuelle...
	{
	echo ' [ '.$i.' ] '; 
	}	
	else //Sinon...
	{
	echo ' <a href="index.php?module=admin_member' . $searchInThis . '&page='.$i.'">'.$i.'</a> ';
	}
}
$pluriel = ($nbtotlalmember > 1) ? 's' : NULL;
echo '<br />' . $nbtotlalmember . ' membre' . $pluriel . ' enregistré' . $pluriel;
?>
</p>
</td></tr>
</table>

<br />

<table width="90%">
<tr><th colspan="2">Ajout d'un membre</th></tr>
<?php 

if (!empty($addMemberError)) {

	echo '<tr><td colspan="2" style="background-color:#c0c0c0;border-style:solid;border-color:red;">'."\n";
	
	foreach($addMemberError as $e) {
	
		echo $e.'<br />'."\n";
	}
	
	echo '</td></tr></ul>';
}

?>

<tr><td width="50%">



	<table>
	<form method="post" action="index.php?module=admin_member">
	<tr><th colspan="2">Information Membre</th></tr>
	<tr><td width="50%">
	Nom d'utilisateur
	</td><td>
	<input type="text" name="loginM" />
	</td></tr>
	<tr><td width="50%">
	Adresse e-mail
	</td><td>
	<input type="text" name="mailM" />
	</td></tr>	
	<tr><td width="50%">
	Mot de passe
	</td><td>
	<input type="text" name="passM" />
	</td></tr>	
	<tr><td width="50%">
	Mot de passe<br /> (encore)
	</td><td>
	<input type="text" name="passM2" />
	</td></tr>
	<tr><td colspan="2" align="center"><input type="submit" name="add_member" value="Ajouter" /></td></tr>
	</form>
	</table>

</td>

<td width="50%">

	<table>
	<tr><th>Option</th></tr>
	<tr><td>
		Group etc...
	</td></tr>
	</table>

</td></tr>
</table>
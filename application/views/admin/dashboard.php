<?php 
if (!empty($errors)) {

	echo '<ul>'."\n";
	
	foreach($errors as $e) {
	
		echo '	<li>'.$e.'</li>'."\n";
	}
	
	echo '</ul>';
}
?>

<p><span title="Note du développeur : 
Aucune erreurs ne devrai jamais apparaitre,
N'hésitez pas à me transmettre les rapports">Alertes</span></p>
<?php 
if ($erreur_alerte['bool'])
{
alerte('Alerte : une ou plusieurs erreurs détect&eacute;es.<br /><center><a href="'.__CW_PATH . '/index.php?module=admin&action=alerte">Voir les alertes</a></center>', true);
}
else
{
valide('Aucune alerte',true);
}
?>

<table width="90%" border="1">
<tr align="center" valign="top">
	<td width="50%">


		<table width="90%" border="1" class="table_style">
		<tr>
			<td>Utilisateur en ligne</td>
		</tr>
		<tr>
			<td>
				<table width="90%">
				<tr>
					<th>Pseudo</th>
					<th>Dernière visite</th>
					<th>Lieu</th>
				</tr>
				<?php foreach ($online as $uniquID => $data)
				{
				echo '<tr title="' . $data['ip'] . '">
					<td>' . $data['pseudo'] . '<br />
					<a href="http://www.ip-adress.com/ip_tracer/' . $data['ip'] . '">' . $data['ip'] . '</a>
					</td>
					<td>' . getRelativeTime($data['time']) . '</td>
					<td><a href="' . $data['url'] . '">' . $data['titre'] . '</a></td>
				</tr>';
				}
				?>
				</table>
			</td>
		</tr>
		</table>
	</td>
	<td>
		<form method="post">
		<table width="90%" border="1" class="table_style">
		<tr>
			<td>Bloc-Notes</td>
		</tr>
		<tr>
			<td align="center">
<textarea id="notepad" name="notepad" cols="15" rows="10" style="height:243px;width: 100%;"><?php echo (empty($notepad)) ? 'Pas de notes' : $notepad; 

?></textarea></td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="submit" value="Enregistrer" /></td>
		</tr>
		</table>
		</form>
		
		<br />
		
		<table width="90%" border="1">
		<tr>
			<td>Actualité Crystal-Web</td>
		</tr>
		<tr>
			<td>

<?php 
if (!empty($rss)) {
foreach($rss as $tab) {
echo '<div class="news_box">
<div class="news_box_title"><a href="'.$tab[1].'" target="_blank">'.$tab[0].'</a></div>
<div class="news_box_date">posté le '.date("d/m/Y",strtotime($tab[3])).'</div>
'.$tab[2].' <a href="'.$tab[1].'" target="_blank">Lire tout l\'article</a>
</div>';
}
}
?>
			
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
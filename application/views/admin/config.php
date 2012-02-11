<form method="post">
<table width="90%">
<tr><th colspan="2">Configuration</th></tr>

<tr><td width="30%">Titre du site</td><td><input type="text" name="sitetitle" value="<?php echo $config['sitetitle']; ?>" /> </td></tr>

<tr title="Facultative, cette balise est utilis&eacute;e par certains moteurs de recherche qui g&ecirc;n&egrave;rent un classement des sites par cat&eacute;gorie. 

Choisissez sa valeur en fonction du th&egrave;me g&eacute;n&eacute;ral de votre site. 

Ins&eacute;rer cette meta sur votre page d'accueil ne peut qu'&ecirc;tre un plus en mati&egrave;re de r&eacute;f&eacute;rencement."><td>Cat&eacute;gorie</td><td><input type="text" name="category" value="<?php echo $config['category']; ?>" /></td></tr>

<tr title="La balise meta content-language pr&eacute;cise aux robots la langue dans laquelle est &eacute;crit le site.
Elle permet aux moteurs nationaux de v&eacute;rifier l'opportunit&eacute; d'indexer un site et aux outils internationaux de l'affecter dans le r&eacute;pertoire r&eacute;gional ad&eacute;quat. 

Il est pr&eacute;f&eacute;rable et conseill&eacute; de ne pas l'omettre."><td>Langue du site</td><td>
<select name="language">
<?php 
foreach ($language as $key => $lng)
{
	if ($key == $config['language'])
	{
	echo '<option selected="selected" value="' . $key . '">' . $lng . '</option>';
	}
	else
	{
	echo '<option value="' . $key . '">' . $lng . '</option>';
	}
} 
?>
</select>

</td></tr>

<tr title="Placez-y un r&eacute;sum&eacute; de votre site web aussi d&eacute;taill&eacute; que possible.

Vos principaux mots-cl&eacute;s doivent imp&eacute;rativement figurer ici.
Veillez, cependant, &agrave; ce que la phrase ait un sens et ne se limite pas à une juxtaposition de termes.
"><td>Description</td><td><input type="text" name="description" value="<?php echo $config['description']; ?>" /></td></tr>

<tr title="Certains moteurs de recherche ne prennent en compte que les 400 premiers caract&egrave;res. Veillez donc &agrave; bien placer en t&ecirc;te vos mots-cl&eacute;s les plus importants. 

Vous pouvez, bien entendu, les d&eacute;cliner au masculin, au f&eacute;minin, au singulier et au pluriel (ami, amie, amis, amies). Mais il est pr&eacute;f&eacute;rable, dans ce cas, de ne pas les juxtaposer et de les s&eacute;parer par quelques autres mots-cl&eacute;s. 

En effet, les robots assimilent les r&eacute;p&eacute;titions &agrave; une tentative de spamindexing et risquent de rejeter votre page. 
"><td>Mots-cl&eacute;s</td><td><input type="text" name="keyword" value="<?php echo $config['keyword']; ?>" /></td></tr>
</table>

<br />

<table>
<tr><th colspan="2">Th&egrave;me</th></tr>
<tr><td width="30%">Choix du th&egrave;me</td><td>
<select name="theme">
<?php 
foreach ($list_dir as $key => $theme)
{
	if ($theme == $config['theme'])
	{
	echo '<option selected="selected" value="' . $theme . '">' . $theme . '</option>';
	}
	else
	{
	echo '<option value="' . $theme . '">' . $theme . '</option>';
	}
} 
?>
</select>
</td></tr>
</table>

<p align="center"><input type="submit" value="Enregistrer" name="config" /></p>
</form>
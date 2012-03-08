<?php
/**
* @title Forum | Liste des sujet
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Listing des sujet de la catégorie courrante
*/

?>
<style type="text/css">
.lili_listemessage{
border: 1px solid gray;
}
.lili_listemessage thead{
background-color: gray;
color: #fff;
}
</style>
<?php
/***************************************
*	Création du tableau de groupe
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));

/***************************************
*	Aucun topic dans ce sujet
***************************************/
if (empty($listSujet[0]->sujet_id))
{

	echo '<p>Aucun topic dans ce sujet, commencé une discution:<br>';
	/***************************************
	*	Test si l'utilisateur peut poster un nouveau topic
	***************************************/

	if ($listSujet[0]->auth_topic <= $this->mvc->Session->user('level') OR in_array($listSujet[0]->groupid, $groupList) OR $groupList[0] == '*')
	{
	echo '<a href="' . Router::url('forum/addpost/slug:'.$listSujet[0]->name.'/id:'.$listSujet[0]->Sid) . '" class="btn">Nouveau topic</a>';	
	}
	echo '</p>';
	
	
}
else
{
	/***************************************
	*	Test si l'utilisateur peut poster un nouveau topic
	***************************************/
	if ($listSujet[0]->auth_topic <= $this->mvc->Session->user('level') OR in_array($listSujet[0]->groupid, $groupList) OR $groupList[0] == '*')
	{
	echo '<a href="' . Router::url('forum/addpost/slug:'.$listSujet[0]->name.'/id:'.$listSujet[0]->Sid) . '" class="btn">Nouveau topic</a>';	
	}
?>
<div style="margin:0 0 20px 0;"> </div>

<table class="lili_listemessage">
	<thead>
		<tr>
			<th colspan="2">&nbsp;</th>
			<th>Titre du sujet</th>
			<th>Page</th>
			<th>Créateur</th>
			<th>Rép.</th>
			<th>Dernier message</th>
		</tr>
	</thead>
	<tbody>
	<?php
	/***************************************
	*	Liste des sujets
	***************************************/
	foreach($listSujet AS $k=>$v): ?>
		<tr>
			<td>pic</td>
			<td><?php echo $v->icone; ?></td>
			<td><a href="<?php echo Router::url('forum/topic/slug:'.$v->titre.'/id:'.$v->Tid); ?>"><?php echo stripcslashes($v->titre); ?><br><?php echo stripcslashes($v->sous_titre); ?></a></td>
			<td><?php
				//Calcul du nombre de page, par rapport au nombre d'article
				//et affiche au dessus de 1 lien/?page=2 lien/?page=3 etc
				$Snb_page = 1;
				if ($v->nb_post>0)
				{
				$Snb_page = ceil( $v->nb_post / 10 );
				}
				
				echo $Snb_page;
				?></td>
			<td><?php echo (!empty($v->Alogin)) ? $v->Alogin : 'Visiteur'; ?></td>
			<td><?php echo $v->nb_post; ?></td>
			<td>Le 24/02/2011 à 18:23:31<br><?php echo (!empty($v->Rlogin)) ? $v->Rlogin : 'Visiteur'; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div style="margin:0 0 20px 0;"> </div>
<?php 
	/***************************************
	*	Test si l'utilisateur peut poster un nouveau topic
	***************************************/
	if ($listSujet[0]->auth_topic <= $this->mvc->Session->user('level') OR in_array($listSujet[0]->groupid, $groupList) OR $groupList[0] == '*')
	{
	echo '<a href="' . Router::url('forum/addpost/slug:'.$listSujet[0]->name.'/id:'.$listSujet[0]->Sid) . '" class="btn">Nouveau topic</a>';	
	}

} //  !!(empty($listSujet[0]->sujet_id))

echo pagination($nb_page);


?>
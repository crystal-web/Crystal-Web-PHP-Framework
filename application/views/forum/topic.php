<?php
/**
* @title Forum | Liste des posts d'un topic
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Listing des post du topic courant
*/

?>
<style type="text/css">
.lili_listemessage{
border: 1px solid gray;
}
.lili_listemessage thead{
background-color: #222;
color: #fff;
}
.lili_infomembre
{
width:165px;
border-right: 1px solid gray;
}
.lili_infopost{
background-color: gray !important;
color: #fff;
}
.lili_avatar{
width: 90px;
height: 110px;
background-color:#fff;
text-align:center;
margin: auto;
border: 1px solid gray;
}
.lili_avatar img{
margin-top:7px;
}
form .input {
margin-right: 150px;
}
</style>
<?php loadFunction('bbcode'); ?>
<table class="lili_listemessage">
	<thead>
		<tr>
			<th>Auteur</th>
			<th>Message</th>
		</tr>
	</thead>
	
	<tbody>
	<?php
	/***************************************
	*	Liste les postes
	***************************************/
	foreach($listTopic AS $k=>$v):
	$v->login = (empty($v->login)) ? 'Visiteur' : $v->login;
	?>
		<tr class="lili_infopost">
			<td id="post<?php echo $v->postId; ?>"><?php echo $v->login; ?></td>
			<td><a href="<?php echo Router::url('forum/topic/slug:'.$v->titre.'/id:'.$v->topicId).'?page='.$page.'#post'.$v->postId; ?>">#</a> Posté <?php echo getRelativeTime($v->created_time); ?></td>
		</tr>
		<tr>
			<td class="lili_infomembre">
			<div class="lili_avatar"><img src="<?php echo get_gravatar($v->mailmember); ?>" alt="<?php echo $v->login; ?>"></div>
			</td>
			<td class="lili_message">
				<?php echo bbcode(stripcslashes($v->message)); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table> 
 
<?php
echo pagination($nb_page);
?>
 
 

	
<?php 
/***************************************
*	Création du tableau de groupe
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));

	/***************************************
	*	Test si l'utilisateur peut repondre au topic
	***************************************/
	if ($listTopic[0]->auth_post <= $this->mvc->Session->user('level')  OR in_array($listTopic[0]->groupid, $groupList) OR $groupList[0] == '*')
	{
	
	/***************************************
	*	Si l'utilisateur a le droit de déplacer le topic
	***************************************/
	if ($this->mvc->Acl->isAllowed('forum','move_it'))
	{
?>
<form method="post" action="<?php echo Router::url('forum/move_it'); ?>">
<div>
<input type="hidden" name="topic_id" value="<?php echo $listTopic[0]->topicId; ?>">
<select name="moveto" id="moveto">
	<option value="2">J'aimerai deplacer</option>
</select>
<input type="submit" value="Déplacer" class="btn primary">
</div>
</form>
<?php
	}	
	
	echo '<form action="' . Router::url('forum/respon/slug:'.$listTopic[0]->titre.'/id:'.$listTopic[0]->topicId) . '?token=' . $this->mvc->Session->getToken() . '" method="post">
'.  $this->mvc->Form->input('message', '', array('type'=>'textarea','editor'=>''));


	/***************************************
	*	Si il n'est pas connecté
	*	Mais qu'il peut répondre on affiche le captcha
	***************************************/
	if (!$this->mvc->Session->isLogged())
	{
	echo '<div class="clearfix">
		<label for="captcha">Captcha : </label>
		<div class="input">
			' . $captcha_img.$captcha_hidden.$captcha_input . '
			<span class="help-block">Clique pour changé les couleurs</span>
		</div>
	</div>';
	}
	
echo $this->mvc->Form->input('submit', 'Répondre', array('type' => 'submit', 'class'=>'btn success'));
echo '</form>';
	}


?>

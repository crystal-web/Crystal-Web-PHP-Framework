<?php
/**
* @title Forum | Repondre
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Repondre a une discution
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


<form action="?token=<?php echo $this->mvc->Session->getToken(); ?>" method="post">
<?php
echo $this->mvc->Form->input('message', '', array('type'=>'textarea','editor'=>''));

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

?>
</form>

<h1>Réponse du sujet</h1>
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
	$page = $nb_page-$page+1;
	/***************************************
	*	Liste des derniere messages postes sur le sujet
	***************************************/
	foreach($listTopic AS $k=>$v):
	
	?>
		<tr class="lili_infopost">
			<td id="post<?php echo $v->postId; ?>"><?php echo $v->login; ?></td>
			<td><a href="<?php echo Router::url('forum/topic/slug:'.$v->titre.'/id:'.$v->topicId).'?page='.$page.'#post'.$v->postId; ?>">#</a> Posté <?php echo getRelativeTime($v->created_time); ?></td>
		</tr>
		<tr>
			<td class="lili_infomembre">
			<div class="lili_avatar"><img src="<?php echo get_gravatar($v->mailmember); ?>" alt="<?php echo $v->login; ?>"></div>
				Ville : Mesnil-saint-blaise<br>
				Pays : Belgique
			</td>
			<td class="lili_message">
				<?php echo bbcode(stripcslashes($v->message)); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
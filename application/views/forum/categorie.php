<?php
/**
* @title Forum | Les categorie
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Categorie 
*/
?>
<style type="text/css">
.lili_listemessage{
border: 1px solid gray;
}
.lili_listemessage thead{
background-color: #333;
color: #fff;
}
.lili_infopost{
background-color: whiteSmoke;
}
.lili_infopost td{
font-weight:bold;
}

</style>
<table class="lili_listemessage">
	<thead>
		<tr>
			<th colspan="2">Cat&eacute;gorie</th>
			<th>Sujets</th>
			<th>R&eacute;ponses</th>
			<th>Dernier message</th>
		</tr>
	</thead> 
	<tbody>
<?php
$lastCat=NULL;
/***************************************
*	Liste des categorie
***************************************/
foreach($listCat AS $k=>$v):

if ($v->Cname != $lastCat && !empty($v->Sname))
{
// Crée le titre de la catégorie 
echo '<tr class="lili_infopost"><td colspan="5"><a href="'.Router::url('forum/cat/slug:'.$v->Cname.'/id:'.$v->Cid).'">'.$v->Cname.'</a><br>'.$v->Cdescription.'</td></tr>';
$lastCat = $v->Cname;
}

if (!empty($v->Sname))
{
?>
		<tr>
			<td><?php echo $v->icone; ?></td>
			<td><?php echo '<a href="'.Router::url('forum/sujet/slug:'.$v->Sname.'/id:'.$v->Sid).'">'.$v->Sname.'</a><br>'.stripcslashes($v->Sdescription); ?></td>
			<td class="nbtopic"><?php echo  number_format($v->nb_topic,0, '', ' '); ?></td>
			<td class="nbpost"><?php echo number_format($v->Snb_post,0, '', ' '); ?></td>
			<td><?php
			if (!empty($v->created_time))
			{
				if ($v->edited_time=='0')
				{
				$pseudo = (!empty($v->Alogin)) ? $v->Alogin : 'Visiteur';
				$nb_page = ceil( $v->Pnb_post / 10 );
				echo getRelativeTime($v->created_time).'<br>par '.$pseudo.'<br>dans <a href="'.Router::url('forum/topic/slug:'.$v->titre.'/id:'.$v->Tid).'?page='.$nb_page.'#post'.$v->last_post_id.'">'.stripcslashes($v->titre).'</a>';
				}
				else
				{
				
				echo getRelativeTime($v->edited_time).'<br>par '.$pseudo.'<br>dans <a href="'.Router::url('forum/topic/slug:'.$v->titre.'/id:'.$v->Tid).'">'.stripcslashes($v->titre).'</a>';
				}
			}else{echo 'Aucun sujet';}  ?></td>
		</tr>
<?php 
}
endforeach;
?>
	</tbody>
</table>
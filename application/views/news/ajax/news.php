<?php
foreach ($news as $data)
{
echo '<div style="padding-bottom:15px;" class="item" id="'.$data['id'].'">
<h2><a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&' . $data['titre']) . '">' . $data['titre'] . '</a>'; ?><span class="right" style="color:red;font-size:12px;padding-top:3px;">R&eacute;dig&eacute; le <?php echo dates($data['date'], 'fr_date'); ?></span></h2>
<?php echo TronqueHtml($data['content'], 280, ' ', ' ...'); 

	echo '<span class="text-right"><a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&' . $data['titre']) . '"  style="color: #3485CC;font-weight:bold;">Lire la suite ...</a></span></div>';
	
	if ($com_actif == true)
	{
	$plurial = ($data['count'] > 1) ? 's' : '';
	?>
	<p>
	<?php

	if ($data['count'] > 0){
	echo '<hr width="90%" />
	<div class="clearfix comment">
	<a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&' . $data['titre']) . '#com">
	<span class="commentview">Voir le' . $plurial . ' commentaire' . $plurial . ' (' . $data['count'] . ')</span>
	</a></div>';
	}

	 ?></p>
	<?php 
	}


}
?>
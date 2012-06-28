<?php
loadFunction('TronqueHtml');
foreach ($article as $data)
{
echo '<div class="clearfix">

<h2><a href="' . Router::url('article/post/slug:' . clean($data->titre, 'slug') . '/id:' . $data->id) . '">' . clean($data->titre, 'str') . '</a>';


	echo '<span class="cal m'.date('n', $data->date).' d'.date('j', $data->date).'">
<span class="m">'.date('M', $data->date).'</span>
<span class="d">'.date('j', $data->date).'</span>
</span>';
	echo '</h2>
	<div style="margin-bottom: 18px;" class="item well" id="'.$data->id.'">
	'.TronqueHtml(clean($data->content, 'html'), 280, ' ', ' ...');
	echo '<span class="text-right"><a href="' . Router::url('article/post/slug:' . clean($data->titre, 'slug') . '/id:' . $data->id) . '"  style="color: #3485CC;font-weight:bold;">Lire la suite ...</a></span>';	



	echo '</div>';
	
	if ($com_actif == true)
	{
	$plurial = ($data->count > 1) ? 's' : '';
	?>
	<p><?php

	if ($data->count > 0){
	echo '<hr width="90%" />
	<div class="clearfix comment">
	<a href="' .Router::url('article/post/slug:' . clean($data->titre, 'slug') . '/id:' . $data->id) . '/#com">
	<span class="commentview">Voir le' . $plurial . ' commentaire' . $plurial . ' (' . $data->count . ')</span>
	</a></div>';}

	 ?></p>
	 
	</div>
	<?php 
	}



}
?>
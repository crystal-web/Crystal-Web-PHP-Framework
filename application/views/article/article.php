<?php
$showDate=false; 
if (!preg_match('#Java#', $_SERVER['HTTP_USER_AGENT']))
{
$showDate = true;
?>
<script type="text/javascript">
$(window).scroll(function(){
	if($(window).scrollTop() == $(document).height() - $(window).height()){
		$.ajax({
			url : "/article/ajax/?<?php echo ($isCategory) ? 'cat='.$isCategory.'&' : NULL; ?>lastid=" + $(".item:last").attr("id"),
			success: function(html){
				if(html){
					$(".mArticle").append(html);
				}
			}
		});
	}
});

</script>
<div class="mArticle">
<?php
}


if ($edito_actif == 'y')
{
echo '<h2>' . $edito_title . '</h2>' . $edito_content;
}




loadFunction('TronqueHtml');
foreach ($article as $data) 
{
echo '<div class="clearfix">
<div style="margin-bottom: 18px;" class="item" id="'.$data->id.'">
<h2><a href="' . Router::url('article/post/slug:' . $data->titre . '/id:' . $data->id) . '">' . stripcslashes($data->titre) . '</a>';


	if ($showDate) 
	{
	echo '<span class="cal m'.date('n', $data->date).' d'.date('j', $data->date).'">
<span class="m">'.date('M', $data->date).'</span>
<span class="d">'.date('j', $data->date).'</span>
</span>';
	echo '</h2>'.TronqueHtml(stripcslashes($data->content), 280, ' ', ' ...');
	echo '<span class="text-right"><a href="' . Router::url('article/post/slug:' . $data->titre . '/id:' . $data->id) . '"  style="color: #3485CC;font-weight:bold;">Lire la suite ...</a></span>';	
	}
	else
	{
	echo '</h2>'.$data->content; 
	}


	echo '</div>';
	
	if ($com_actif == true)
	{
	$plurial = ($data->count > 1) ? 's' : '';
	?>
	<p><?php

	if ($data->count > 0){
	echo '<hr width="90%" />
	<div class="clearfix comment">
	<a href="' .Router::url('article/post/slug:' . $data->titre . '/id:' . $data->id) . '/#com">
	<span class="commentview">Voir le' . $plurial . ' commentaire' . $plurial . ' (' . $data->count . ')</span>
	</a></div>';}

	 ?></p>
	 
	</div>
	<?php 
	}


}
?>

</div>
<?php
if ($pagi_actif=='y')
{
echo pagination($nb_page);
}
else
{
?>
<noscript>
<?php echo pagination($nb_page); ?>
</noscript>
<?php
}
?>

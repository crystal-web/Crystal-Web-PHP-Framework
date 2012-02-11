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
			url : "ajax.php?module=news&lastid=" + $(".item:last").attr("id"),
			success: function(html){
				if(html){
					$(".mNews").append(html);
				}
			}
		});
	}
});

</script>
<?php
}

if ($edito_actif == true)
{
echo '<h2>' . $edito_title . '</h2>' . $edito_content;
}

echo '<div class="mNews">';



foreach ($news as $data)
{
echo '<div style="padding-bottom:15px;" class="item" id="'.$data['id'].'">
<h2><a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&' . $data['titre']) . '">' . $data['titre'] . '</a>';

	if ($showDate)
	{
	echo '<span class="right" style="color:red;font-size:12px;padding-top:3px;">R&eacute;dig&eacute; le '.dates($data['date'], 'fr_date').'</span>';
	echo '</h2>'.TronqueHtml($data['content'], 280, ' ', ' ...');
	echo '<span class="text-right"><a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&' . $data['titre']) . '"  style="color: #3485CC;font-weight:bold;">Lire la suite ...</a></span>';
		
	}
	else
	{
	echo '</h2>'.$data['content']; 
	}


	echo '</div>';
	
	if ($com_actif == true)
	{
	$plurial = ($data['count'] > 1) ? 's' : '';
	?>
	<p><?php

	if ($data['count'] > 0){
	echo '<hr width="90%" />
	<div class="clearfix comment">
	<a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&' . $data['titre']) . '#com">
	<span class="commentview">Voir le' . $plurial . ' commentaire' . $plurial . ' (' . $data['count'] . ')</span>
	</a></div>';}

	 ?></p>
	<?php 
	}


}

echo '</div>';	// END div mNews


/*
if ($pagi_actif)
{
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
          echo ' <a href="' . url('index.php?module=news&page='.$i) . '">'.$i.'</a> ';
     }
}
echo '</p>';
}*/
?>

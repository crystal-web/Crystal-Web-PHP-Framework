<?php
echo '<section id="article">';

if ($this->mvc->Session->isLogged())
{

	if ( $this->mvc->Acl->isGrant() OR $output->id_auteur == $this->mvc->Session->user('idmember') )
	{
	?><span style="float:right;">
	<a href="#"  data-controls-modal="delpost" data-backdrop="true" data-keyboard="true" onclick="delPost('<?php echo Router::url('article/admin_delpost/id:'.$output->id); ?>');">
		<img src="<?php echo __CDN; ?>/files/images/icons/eraser.png" alt="Editon">
	</a>
	<a href="<?php echo Router::url('article/admin_addpost/id:'.$output->id); ?>">
		<img src="<?php echo __CDN; ?>/files/images/icons/newspaper--pencil.png" alt="Suppression">
	</a></span>
	<?php
	}
}

// Article

echo '<p><span class="text-right" style="color:red;font-size:12px;">R&eacute;dig&eacute; le '. dates($output->date, 'fr_date') . '</span></p>
	' . stripcslashes($output->content) . '
</section>';
 
 
if ($com_actif=='y')
{
// Deux colonnes Commentaires
echo '<section id="commentaires">';
	loadFunction('bbcode');
	
	// Le nombre de commentaire
	if ($nbcom != 0)
	{
	$plurial = ($nbcom > 1) ? 's' : '';
	echo '<h4 id="com">' . $nbcom . ' commentaire' . $plurial . '</h4>';
	echo '<ol style="padding: 0px;list-style: none;margin: 10px 0px 20px;">';


		foreach ($getComm as $data)
		{
		
	echo '<li>
		
		<img alt="" src="' . get_gravatar($data->mail) . '" style="float: left;
	border: 1px solid #ACA288;
	display: block;
	margin-right: 10px;
	margin-bottom: 5px;" height="32" width="32" />
		<p style="color: #B99316;
	margin-top: 10px;
	padding: 0px;
	float: left;">Commentaire de <span style="color:#000;font-weight:bold;">' . $data->pseudo . '</span> le ' . dates($data->Cdate, 'fr_date') . '  </p>
		<div style="clear: both;
	width: 100%;
	color: #333;
	border-top: 1px dotted #B99316;
	padding-top: 10px;
	margin-bottom: 15px;"><p>' .stripcslashes(bbcode_format($data->content)) . '</p></div>
		
		</li>';

		}
	echo '</ol>';

	}
	else
	{
	echo '<h4>Pas de commentaire</h4>';
	}



?>


<form action="<?php echo Router::url('article/commentpost/id:' . $output->id).'?token='.$this->mvc->Session->getToken(); ?>" method="post">
<fieldset>
	<legend>Laisser un commentaire</legend>

	<?php echo $this->mvc->Form->input('id_article', 'hidden', array('default' => $output->id)); ?>
	<?php echo $this->mvc->Form->input('pseudo', 'Pseudo:  ', array('placeholder' => 'Anne Onyme')); ?>
	<?php echo $this->mvc->Form->input('mail', 'E-mail: ', array('addon' => '@','placeholder' => 'Anne@Onyme.moi')); ?>
	<?php echo $this->mvc->Form->input('website', 'Site web: ', array('addon' => 'http://','placeholder' => 'www.crystal-web.org')); ?>
	<?php echo $this->mvc->Form->input('content', 'Commentaire: ', array('type' => 'textarea', 'editor' => '')); ?>
	
	<div class="clearfix">
		<div class="input">
			<input type="submit" value="Envoyer" class="btn success">
		</div>
	</div>
	
</fieldset>
</form>

<!-- End commentaires -->
</section>







<?php 
} // END Commentaire actif
else
{
?>
	<!-- Google +1 part1 -->
<script type="text/javascript" src="http://apis.google.com/js/plusone.js">
  {lang: 'fr'}
</script>
<div style="padding-top:15px;font-size:10pt;width:300px;margin:auto;">

	<div style="float: left; margin-bottom: 7px;">
	<!-- bouton google +1 part2 -->
	<g:plusone size="tall"></g:plusone>
	</div>

	<div style="float: left; margin-left: 12px; margin-bottom: 7px;">
	<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweeter</a>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</div>

	<div style="float: left; margin-left: 30px; margin-right: 20px; margin-top: 0px;">
	<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
	<script type="in/share" data-counter="top"></script>
	</div>

	<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(Router::url('article/cat/slug:'.$output->categorie.'/id:'.$output->categorieid)); ?>&amp;layout=box_count&amp;show_faces=false&amp;width=65&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=65" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:65px; height:65px;" allowTransparency="true"></iframe>
</div>
<?php
}
?>

<?php
if ($this->mvc->Session->isLogged())
{
	if ( $this->mvc->Acl->isGrant() OR $output->id_auteur == $this->mvc->Session->user('idmember') )
	{
?>
<script type="text/javascript">
function delPost(postHref)
{
document.getElementById("del").href = postHref + '?token=<?php echo $this->mvc->Session->getToken(); ?>';
$('#modalTitle').text('Alerte...');
$('#modalText').html('<p>Supprimer l\'article ?</p>');
}
</script>
<div id="delpost" class="modal hide fade">
	<div class="modal-header">
		<a href="#" class="close">&times;</a>
		<h3 id="modalTitle"></h3>
	</div>
	
	<div class="modal-body" id="modalText"></div>
	<div class="modal-footer">
		<a href="#" class="btn" onclick="$('#delpost').modal('hide');">NON</a>
		<a href="#" id="del" class="btn danger">OUI</a>
	</div>
</div>

<?php } // IsGrant or author
} // isLogged
?>
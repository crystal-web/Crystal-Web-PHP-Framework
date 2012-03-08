


<?php 
if ($com_actif)
{
// Article
echo '<section id="article">
	<p><span class="text-right" style="color:red;font-size:12px;">R&eacute;dig&eacute; le '. dates($output->date, 'fr_date') . '</span></p>
	' . $output->content . '
</section>';

// Deux colonnes Commentaires
echo '<section id="commentaires">
<div class="row">
<div class="span11">';
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


include_once __SITE_PATH . "/ckeditor/ckeditor.php";


// Create a class instance.
$CKEditor = new CKEditor();

// Path to the CKEditor directory.
$CKEditor->basePath = __CW_PATH . '/ckeditor/';

// Creation de l'editeur avec la config BBcode $CKEditorConfig
$CKEditor->editor("commentaire", '', $CKEditorConfig);
//*/
?>
</div> <!-- End commentaires -->
	<div class="span5">

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

	<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(Router::url('news/cat/slug:'.$output->categorie.'/id:'.$output->categorieid)); ?>&amp;layout=box_count&amp;show_faces=false&amp;width=65&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=65" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:65px; height:65px;" allowTransparency="true"></iframe>
</div>

	<div class="clearfix">
		<!--PUB-260x360-->
		<!--PUB-234x60-->
		<!--PUB-234x60-->
	</div>
	
	</div>
</div>
</section>



<form action="<?php echo Router::url('news/commentpost/id:' . $output->id); ?>" method="post">
<fieldset>
	<legend>Laisser un commentaire</legend>
<?php if (!Auth::isAuth()): ?>
	<div class="clearfix">
		<label for="fpseudo">Pseudo</label>
		<div class="input">
			<input type="text" name="name" id="fpseudo" placeholder="Anne Onyme" />
		</div>
	</div>
	<div class="clearfix">
		<label for="fmail">E-mail</label>
		<div class="input">
			<div class="input-prepend">
				<span class="add-on">@</span>
				<input type="text" name="mail" id="fmail" placeholder="Anne@Onyme.moi" />
				<span class="help-block">
				<span class="label important">Important</span> Ne sera jamais diffus&eacute;</span>
			</div>
		</div>
	</div>
<?php endif; ?>
	<div class="clearfix">
		<label for="fweb">Site web</label>
		<div class="input">
			<div class="input-prepend">
				<span class="add-on">http://</span>
				<input type="text" name="website" id="fweb" placeholder="www.crystal-web.org"/>
			</div>
		</div>
	</div>
	
	<div class="clearfix">
		<div class="input">
			<input type="submit" value="Envoyer" class="btn success" />
		</div>
	</div>	
</fieldset>
</form>


<?php 
} // END Commentaire actif
else
{
echo '<section id="article">
<div class="row">
<div class="span10">
	<p><span class="text-right" style="color:red;font-size:12px;">R&eacute;dig&eacute; le '. dates($output->date, 'fr_date') . '</span></p>
	' . $output->content . '
</div>
<div class="span6"><!--PUB-300x250--></div>
</div>

</section>';
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

	<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(Router::url('news/cat/slug:'.$output->categorie.'/id:'.$output->categorieid)); ?>&amp;layout=box_count&amp;show_faces=false&amp;width=65&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=65" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:65px; height:65px;" allowTransparency="true"></iframe>
</div>
<?php
}
?>


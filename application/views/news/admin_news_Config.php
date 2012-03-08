<form method="post">

<div class="clearfix">
	<label>Titre du module</label>
	<div class="input">
		<input type="text" name="titre" value="<?php echo $titre; ?>">
	</div>
</div>

<div class="clearfix">
	<label>Titre de la page news</label>
	<div class="input">
		<input type="text" name="titre_news" value="<?php echo $titre_news; ?>">
	</div>
</div>

<div class="clearfix">
	<label>Nombre de news par pages</label>
	<div class="input">
		<input type="text" name="postParPage" value="<?php echo $postParPage; ?>">
	</div>
</div>	

<div class="clearfix">
	<label>Activer les commentaires</label>
	<div class="input">
<?php 
if ($com_actif) 
{
echo '<ul class="inputs-list">
<li>
  <label>
	<input type="radio" name="com_actif" value="on" checked="checked">
	<span>Oui</span>
  </label>
</li>
<li>
  <label>
	<input type="radio" name="com_actif" value="off">
	<span>Non</span>
  </label>
</li>
</ul>';
}
else
{
echo '<ul class="inputs-list">
<li>
  <label>
	<input type="radio" name="com_actif" value="on">
	<span>Oui</span>
  </label>
</li>
<li>
  <label>
	<input type="radio" name="com_actif" value="off" checked="checked">
	<span>Non</span>
  </label>
</li>
</ul>';
}
?>
	</div>
</div>





<div class="clearfix">
	<label>Activer l'&eacute;ditot</label>
	<div class="input">
<?php 
if ($edito_actif) 
{
echo '<ul class="inputs-list">
<li>
  <label>
	<input type="radio" name="edito_actif" value="on" checked="checked">
	<span>Oui</span>
  </label>
</li>
<li>
  <label>
	<input type="radio" name="edito_actif" value="off">
	<span>Non</span>
  </label>
</li>
</ul>';
}
else
{
echo '<ul class="inputs-list">
<li>
  <label>
	<input type="radio" name="edito_actif" value="on">
	<span>Oui</span>
  </label>
</li>
<li>
  <label>
	<input type="radio" name="edito_actif" value="off" checked="checked">
	<span>Non</span>
  </label>
</li>
</ul>';
}
?>
	</div>
</div>



<div class="clearfix">
	<label>Titre de l'&eacute;ditoriel:</label>
	<div class="input">
		<input type="text" name="edito_titre" value="<?php echo $edito_title; ?>">
	</div>
</div>

<div class="clearfix">
<?php
include_once "ckeditor/ckeditor.php";

// Create a class instance.
$CKEditor = new CKEditor();

// Path to the CKEditor directory.
$CKEditor->basePath = 'ckeditor/';

// Create a textarea element and attach CKEditor to it.
$CKEditor->editor("edito_contenu", $edito_content);

?>
</div>

<div class="clearfix">
	<div class="input">
		<input type="submit" value="Enregister" class="btn success">
	</div>
</div>

</form>
<form method="post">

<div class="clearfix">
	<label for="titre">Titre : </label>
	<div class="input">
		<input type="text" name="titre" id="titre" value="<?php echo $titre; ?>">
	</div>
</div>

<div class="clearfix">
	<label for="categorie">Cat&eacute;gorie : </label>
	<div class="input">
		<select name="categorie" id="categorie" id="">
<?php

foreach ($listCategorie as $key => $data)
{
echo '<option value="' . $data['id'] . '">' . $data['categorie'] . '</option>';
}
?>
		</select>
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
$CKEditor->editor("news", '');

?>
</div>

<div class="clearfix">
	<div class="input">
		<input type="submit" value="Enregister" class="btn success">
	</div>
</div>

</form>
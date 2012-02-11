<h1>Ajout d'une News</h1>
<form method="post">
<p><label for="categorie">Selectionner une catégorie : </label>

<select name="categorie" id="categorie" style="max-width:180px;">
<?php

foreach ($listCategorie as $key => $data)
{
	if ($data['id'] == $categorie)
	{
	echo '<option value="' . $data['id'] . '" selected="selected">' . $data['categorie'] . '</option>';
	}
	else
	{
	echo '<option value="' . $data['id'] . '">' . $data['categorie'] . '</option>';
	}
}
?>
</select>
</p>

<p><label for="titre">Titre : </label>
<input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" />
</p>

<?php
include_once "ckeditor/ckeditor.php";

// Create a class instance.
$CKEditor = new CKEditor();

// Path to the CKEditor directory.
$CKEditor->basePath = 'ckeditor/';

// Create a textarea element and attach CKEditor to it.
$CKEditor->editor("news", $content);

?>

<p align="center"><input type="submit" value="Enregister" /></p>
</form>
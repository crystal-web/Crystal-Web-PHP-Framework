<?php if (isSet($msg)) echo '<p>' . $msg . '</p>'; 
if (isSet($erreur))
{
echo  '<ul>';
	foreach($erreur AS $key => $value)
	{
	echo '<li>'.$value.'<li>';
	}
echo '</ul>';
}

?>


Avant l'upload d'un plugin, il est fortement conseiller de faire une copie int&eacute;grale de votre site.<br />
Fichier et base de donn&eacute;e, certain pirate n'h&eacute;siterons pas &agrave; d&eacute;truire votre site &agrave; votre insu ou vous voler des informations crucials.

<form action="./index.php?module=admin_plugin&action=add" method="post" enctype="multipart/form-data">
<p align="center"><input type="file" name="upload_plugin" size="30">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo ini_get('upload_max_filesize'); ?>"> 
<input type="submit" value="Uploader"></p>			
</form>
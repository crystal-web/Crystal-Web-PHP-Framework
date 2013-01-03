Avant l'upload d'un plugin, il est fortement conseiller de faire une copie int&eacute;grale de votre site.<br>
Fichiers et base de donn&eacute;e, certain pirate n'h&eacute;siterons pas &agrave; d&eacute;truire votre site &agrave; votre insu ou vous voler des informations crucials.

<form action="<?php echo Router::url('plugin'); ?>" method="post" enctype="multipart/form-data">
<p align="center"><input type="file" name="upload_plugin" size="30">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo ini_get('upload_max_filesize'); ?>"> 
<input type="submit" value="Uploader"></p>
</form>
La version du syst&egrave;me est : <b><?php echo __VER; ?></b><br>
<?php 
if (count($plugin) > 0)
{
?>
<table class="bordered-table zebra-striped">
<thead>
<tr> 
	<th>Nom</th> 
	<th>Description</th> 
	<th width="50">Activer</th> 
	<th>Désinstaller</th> 
</tr>
</thead>
<?php 

foreach ($plugin as $key => $value)
{
	if ($value['activer'] == false){
	$checkfalse = 'checked="checked"';
	$checktrue = '';
	}
	else
	{
	$checkfalse = '';
	$checktrue = 'checked="checked"';
	}
	
	if (isSet($value['admin']))
	{
		$extension=strrchr($value['admin'],'.');
		$extension=strtolower(substr($extension,1));
		if ($extension = 'php')
		{
		$value['admin'] = '<a href="'.Router::url('plugin/manager/slug:'.$key).'">Administration</a>';
		}
		else
		{
		$value['admin']='sans'; 
		}
	}
	else
	{
	$value['admin']='sans'; 
	}
	
$value['sgbd']		= ($value['sgbd']) ? 'oui' : 'non';
$value['cookie']	= ($value['cookie']) ? 'oui' : 'non';
$value['cache']		= ($value['cache']) ? 'oui' : 'non';
echo '<tr>
<form method="POST">
	<td align="center">&nbsp;<b>' . $key . '</b>&nbsp;</td>
	<td>
		<b>Auteur:</b> <a href="'.$value['website'].'" target="_blank">'.$value['author'].'</a> <br>
		<b>Description:</b> '.$value['description'].'<br>
		<b>Compatibilité:</b>' . $value['compatibility'] . '<br>
		<b>Version:</b> '.$value['version'].'<br>
		
		<b>Administration:</b> '.$value['admin'].'<br>
		<b>Utilise SGBD:</b> '.$value['sgbd'].'<br>
		<b>Utilise les cookies:</b> '.$value['cookie'].'<br>
		<b>Utilise le cache:</b>  '.$value['cache'].'<br>
	</td>
	<td>
		<form method="POST">
			<input type="hidden" name="ref" value="' . $key . '">
			<label><input type="radio" name="' . $key . '" value="1" ' . $checktrue . '  onClick="this.form.action=\''.Router::url('plugin').'\';this.form.submit();"> Oui</label><br>
			<label><input type="radio" name="' . $key . '" value="0" ' . $checkfalse . 'onClick="this.form.action=\''.Router::url('plugin').'\';this.form.submit();"> Non</label>
		</form>
	</td>
	<td>
		<form method="POST">
			<input type="hidden" name="refd" value="' . $key . '">
			<input type="submit" name="1" value="Désinstaller">
		</form>
	</td>
</form>
</tr>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>';
 
}
echo '</table>';
}
?>

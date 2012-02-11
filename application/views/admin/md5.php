<h1>Controlleur de fichier</h1>
<p align="center">Contr&ocirc;ler : 
	<a href="index.php?module=admin_md5&dir=fw">le syst&egrave;me</a>
	 - <a href="index.php?module=admin_md5&dir=lb">les librairies</a>
	 - <a href="index.php?module=admin_md5&dir=cn">les controlleurs</a>
	 - <a href="index.php?module=admin_md5&dir=xm">les HTTPRequest</a>
	 - <a href="index.php?module=admin_md5&dir=ic">les includes (global)</a>
 </p>
<p>Certain fichier peuvent avoir été modifier, lors de mise à jour ou de modification de templatte.<br  />
Les plugins ne venant pas de <a href="http://crystal-web.org/">Crystal-web.org</a>, seront d&eacute;clar&eacute; comme non référenc&eacute;
</p>

<p>
<?php 
if (!empty($error)){
echo $error;
}

foreach ($output as $data => $value)
{
	switch ($value){
	case 'red':
	echo '<font color="red">' . $data . ' &agrave; &eacute;t&eacute; modifier</font><br />';
	break;
	case 'green':
	echo '<font color="green">' . $data  . ' est correcte</font><br />';
	break;
	case 'norefered':
	echo '<font color="blue">' . $data  . ' n\'est pas référencé</font><br />';
	break;
	}
}
?>
</p>
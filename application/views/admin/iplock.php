<form method="post" action="index.php?module=admin_iplock&action=add">
<input type="text" name="ipx" style="width:75%" />
<input type="submit" value="Bloqu&eacute; l'ip">
</form>

<?php 
echo 'Cystal-Web &agrave; refus&eacute; l\'acc&ecirc;s &agrave; '.$acces.' IPs<br />';
if (count($tableIp))
{
?>
<table class="table_style" width="100%">
	<?php
	foreach ($tableIp AS $value => $none)
	{
	echo '<tr>
		<td><a href="http://www.ip-adress.com/ip_tracer/'.$value.'" target="_black">'.$value.'</a></td>
		<td><a href="index.php?module=admin_iplock&action=pardonne&ip='.$value.'">pardonnne</a></td>
	</tr>';
	}
	?>
</table>
<?php
}
else
{
echo '<div class="MSGbox MSGinfo"><p>Pas d\'ip bloqu&eacute;</p></div>';
}
?>
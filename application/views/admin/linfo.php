<h1>System Health and Information</h1>
<table class="table_style" width="100%">
<tr>
<th colspan="2">Core</th>
</tr>
<tr>
<?php 
echo "<tr><td><b>OS</b></td><td>" . $data['OS'] . "</td></tr>".
"<tr><td><b>Distribution</b></td><td>" . $data['Distro']['name'] . " - " . $data['Distro']['version'] . "</td></tr>".
"<tr><td><b>Uptime</b></td><td>" . $data['UpTime'] . "</td></tr>".
"<tr><td><b>CPUs </b></td><td>";
foreach ($data['CPU'] as $key => $cpu)
{
echo $cpu['Vendor'] . " - " . $cpu['Model']."  (". sprintf('%.1f',$cpu['MHz'])." GHz)<br />";

}

echo "</td></tr>.
<tr><td><b>Architecture</b></td><td>" . $data['CPUArchitecture'] . "</td></tr>".
"<tr><td><b>Load</b></td><td>Now " . $data['Load']['now'] . " | 5min " . $data['Load']['5min'] . " | 15min " . $data['Load']['15min'] . "</td></tr>".
"<tr><td><b>Hostname</b></td><td>" . $data['HostName'] . "</td></tr>"
;
?>
</tr>
</table>

<br />

<table class="table_style" width="100%">
<tr>
<th colspan="4"><b>M&eacute;moire</b></th>
</tr>
<tr>
<th><b>Type</b></th><th><b>Libre</b></th><th><b>Utiliser</b></th><th><b>Total</b></th></tr>

<tr>
<?php 
echo "<tr><td><b>Physique</b></td><td>" . convert($data['RAM']['free']) . "</td>".
"<td>" . convert($data['RAM']['total']-$data['RAM']['free']) . "</td><td>" . convert($data['RAM']['total']) . "</td></tr>".
"<tr>
<td><b>Swap</b></td><td>" . convert($data['RAM']['swapFree']) . "</td>".
"<td>" . convert($data['RAM']['swapTotal']-$data['RAM']['swapFree']) . "</td>
<td>" . convert($data['RAM']['swapTotal']) . "</td></tr>";

foreach ($data['RAM']['swapInfo'] as $ram){
echo "<tr align=\"center\"><td><b>Device</b><br />" . $ram['device'] . "</td>
<td><b>Type</b><br />" . $ram['type'] . "</td>
<td><b>Size</b><br />" . convert($ram['size']) . "</td> 
<td><b>Utiliser</b><br />" . convert($ram['used']) . "</td>
</tr>";
}
?>
</tr>
</table>

<br />

<table class="table_style" width="100%">
<tr>
<th colspan="3"><b>Hardware</b></th>
</tr>
<tr>
<th><b>Type</b></th><th><b>Vendor</b></th><th><b>Device</b></th>
</tr>
<?php 
foreach ($data['Devices'] as $key => $device)
{
echo "<tr>
<td>" .$device['type']. "</td><td>" .$device['vendor']. "</td><td>" .$device['device']. "</td>
</tr>";
}
?>

</table>

<br />

<table class="table_style" width="100%">
<tr>
<th colspan="6"><b>Disque dur</b></th>
</tr>
<tr><th><b>Path</b></th><th><b>Vendor</b></th><th><b>Nom</b></th>
<th><b>Reads</b></th><th><b>Writes</b></th><th><b>Size</b></th></tr>

<?php 
foreach ($data['HD'] as $key => $hd)
{
echo "<tr><td>" . $hd['device'] . "</td><td>" . $hd['vendor'] . "</td><td>" . $hd['name'] . "</td>
<td>" .$hd['reads'] . "</td><td>" . $hd['writes'] . "</td><td>" . convert($hd['size']) . "</td></tr>";
	foreach ($hd['partitions'] as  $key => $part)
	{
echo "<tr><td colspan=\"6\">/dev/sda".$part['number']." - " . convert($part['size']) . "</td></tr>";
	}
}
?>
</table>

<br />

<table class="table_style" width="100%">
<tr>
<th colspan="9"><b>Filesystem Mounts</b></th>
</tr>
<tr><th><b>Device</b></th><th><b>Mount</b></th><th><b>Point</b></th>
<th><b>Filesystem</b></th><th><b>Size</b></th><th><b>Used</b></th>
<th><b>Free</b></th><th><b>Percent Used</b></th></th></tr>
<?php

foreach ($data['Mounts'] as $key => $device)
{
$used_percent = (!empty($device['used_percent'])) ? $device['used_percent'].'%' : 'N/A';
$free_percent = (!empty($device['free_percent'])) ? $device['free_percent'].'%' : 'N/A';

echo "<tr><td>" . $device['device'] . "</td><td>" . $device['mount'] . "</td><td>" . $device['type'] . "</td>
<td>" . convert($device['size']) . "</td><td>" . convert($device['used']) . " (" . $used_percent . ")</td><td>" .  convert($device['free']) . " (" . $free_percent . ")</td>
<td>" . $free_percent . "</td><td>" . $used_percent . "</td></tr>";
}
?>
</table>

<br />

<h1>System PHP</h1>
<?php echo '<img src="' . $_SERVER['PHP_SELF'] .
     '?=' . php_logo_guid() . '" alt="PHP Logo !" />'; ?>
<table class="table_style" width="100%">
<tr><th colspan="2"><b>G&eacute;n&eacute;ral</b></th></tr>
<tr><td width="50%">Serveur</td><td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
<tr><td width="50%">PHP Version</td><td><?php echo phpversion(); ?></td></tr>
<tr><td>EGPCS (global)</td><td>
	<?php
	if (isSet($_SERVER['REGISTER_GLOBALS']))
	{
	echo ($_SERVER['REGISTER_GLOBALS']) ? 'ON' : 'OFF';
	}
	else
	{
	echo 'OFF';
	}
	?></td></tr>
</table>

<br />

<table class="table_style" width="100%">
<tr><th colspan="2"><b>Extention PHP</b></th></tr>
<?php

$phpinfo=parsePHPModules();
foreach(get_loaded_extensions() AS $key => $data)
{

echo '<tr><th colspan="2">&nbsp;</th><tr>
<tr><th colspan="2"><b>' . $data . '</b></th><tr>';
if (array_key_exists($data, $phpinfo)){
	foreach ($phpinfo[$data] as $key => $value)
	{
		if (!is_array($value)){
		echo '<tr><td width="50%">' . $key . '</td><td>' . $value . '</td><tr>';
		}
		else
		{
		echo '<tr><td width="50%">' . $key . '</td><td><table>';
		
			foreach ($value as $nkey => $data)
			{
			echo '<tr><td width="50%">' . $nkey . '</td><td>' . $data . '</td><tr>';
			}
		echo '</table></td><tr>';
		}
	}
}

}

?>
</table>
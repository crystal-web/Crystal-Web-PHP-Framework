<h1>Rapport d'erreurs</h1>

<table width="100%" class="table_style">
<tr><th colspan="2">Transmettre</th></tr>
</tr><td>
Transmettre le rapport au d&eacute;veloppeur.
</td>
<td>
<form method="post">
<input type="hidden" name="bugtracker">
<input type="submit" value="Transmettre">
</form>
</td></tr>
</table>
<br />

<table width="100%" class="table_style">
<tr><th colspan="2">Effacer</th></tr>
</tr><td>
Effacer le rapport
Définitif!
</td>
<td>
<form method="post">
<input type="hidden" name="poke" value="<?php echo $_SESSION['poke']; ?>">
<input type="submit" value="Effacer">
</form>
</td></tr>
</table>

<br />

<table width="100%" class="table_style">
<tr><th colspan="2">Erreurs archiv&eacute;es</th></tr>
<tr><th>Description</th><th>Date</th></tr>
<?php
foreach ($alerte AS $date => $data)
{
?>
<tr>
<td>
<?php
echo '<b>Type : </b>' . $data['type'] . ' ' . $data['msg'].'<br />';
echo '<b>Ligne : </b>' . $data['errline'] . ' ' . $data['errfile'].'<br />';
?>

<div style="margin:20px; margin-top:5px"><div class="quotetitle"><b>More : </b> <input type="button" value="Afficher" style="width:45px;font-size:10px;margin:0px;padding:0px;" onclick="if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = '';        this.innerText = ''; this.value = 'Cacher'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerText = ''; this.value = 'Afficher'; }" /></div>
<div class="quotecontent"><div style="display: none;">
<table width="100%">
<tr>
	<td>
<textarea rows="30" cols="60">
<?php
echo $data['more'];
?>
</textarea>
	</td>
</tr>
</table>
</div></div></div>

</td>
<td><?php echo date("d/m/Y H:i:s",$date); ?></td>

</tr>
<?php 
}
?>
</table>

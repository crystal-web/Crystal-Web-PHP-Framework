<p align="center">
	<a href="<?php echo Router::url('error/delete'); ?>" class="btn error">Supprimer les erreurs</a>
</p>
<div class="well">
<?php
foreach ($alerte AS $k => $data)
{
echo '<p><a href="' . Router::url('error/delete/id:'.$k) . '" class="btn">Supprimer cette erreur</a></p>' .
	'<b>Controller : </b>' . $data['controller'].'<br>' .
	'<b>Type : </b>' . $data['type'] . ' ' . $data['msg'].'<br>' .
	'<b>Ligne : </b>' . $data['errline'] . ' ' . $data['errfile'].'<br>' . 
	'<b>Date : </b>' . dates($data['date'], 'fr_date') . '<br>';
?>
<div style="margin:20px; margin-top:5px">
	<div class="quotetitle">
		<b>More : </b>
			<input type="button" value="Afficher" style="width:45px;font-size:10px;margin:0px;padding:0px;" onclick="if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = '';        this.innerText = ''; this.value = 'Cacher'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerText = ''; this.value = 'Afficher'; }" />
	</div>
	<div class="quotecontent">
		<div style="display: none;">
			<textarea rows="30" cols="60" style="width:100%;">
<?php
echo $data['more'];
?>
			</textarea>
		</div>
	</div>

</div>
<?php 
}
?>
</div>

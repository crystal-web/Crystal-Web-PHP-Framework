<h1>Commentaire en attente</h1>
<form method="get" action="index.php?module=admin_news&action=commentaire">

<input type="hidden" name="module" value="admin_news">
<input type="hidden" name="action" value="commentaire">

<div class="clearfix">
	<label for="aff">Afficher : </label>
	<div class="input">
		<select name="aff" id="aff" style="max-width:180px;" onchange="this.form.submit()">
		<option>Selectionnez un affichage</option>
			<optgroup label="Afficher">
				<option value="1">En attente</option>
				<option value="2">Approuv&eacute;s</option>
				<option value="3">Ind&eacute;sirable</option>
			</optgroup>
		</select>
	</div>
</div>

</form>

<table>
<thead>
	<tr>
		<th colspan="2">Pseudo</th>
		<th>Commentaire</th>
		<th>Date</th>
		<th>IP</th>
		<th>action</th>
	</tr>
</thead>
<tbody>



<?php
include_once __APP_PATH.'/function/bbcode.php'; 

foreach($commentaire as $key => $data)
{
$website = ($data['website'] != NULL) ? ' onclick="window.open(\'' . $data['website'] . '\',\'_blank\');" style="cursor: pointer;"' : NULL;

	echo '<tr>
		<td ' . $website .'>
			<img alt="" src="' . get_gravatar($data['mail']) . '" style="float: left;
	border: 1px solid #ACA288;display: block;margin-right: 10px;margin-bottom: 5px;" height="32" width="32"></td>
		<td>' . $data['pseudo'] . '</td>
		<td style="max-width:300px;">
			<p>
				<a href="' . url('index.php?module=news&action=post&p=' . $data['id_news'] . '&' . $data['titre']) . '">' . $data['titre'] . '</a>
			</p>
			<br />
			' . stripcslashes(bbcode_format($data['content'], array('w' => 300, 'h' => 300))) . '
		</td>
		<td>' . dates($data['Cdate'], 'fr_date') . '</td>
		<td>' . $data['ip'] . '<br />
			<a href="http://www.ip-adress.com/ip_tracer/'.$data['ip'].'" target="_black">Info IP</a> / <a href="' . __CW_PATH . '/index.php?module=admin_iplock&action=add&ip=' . $data['ip'] . '">LockIP</a>
		</td>
		<td>
			<a href="index.php?module=admin_news&action=commentaire&accept=' . $data['id'] . '&tko=' . $tko . '">Accepter</a>  <a href="index.php?module=admin_news&action=commentaire&refute=' . $data['id'] . '&tko=' . $tko . '">Refuser</a>
		</td>
	</tr>
<tr>
	<td colspan="3">Site web : ' . $data['website'] . '</td>
	<td colspan="3">' . $data['mail'] . '</td>
</tr>

<tr>
<td colspan="6">&nbsp;</td>
</tr>';

}



?>
</tbody>
</table>
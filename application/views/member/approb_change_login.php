<?php
if (count($listMember)==0)
{
$this->mvc->Session->setFlash('Aucune demande pour le moment');
}
else
{
?>
<table class="zebra-striped">
<thead>
<tr>
	<th>Nouveau login</th>
	<th>Motif</th>
	<th>Changer</th>
</tr>
</thead>
<tbody>
<?php

	foreach($listMember AS $k => $v):
	echo '<tr>
		<td>'.clean($v->newlogin, 'extra').'</td>
		<td>'.clean($v->raison, 'str').'<br>
		Actuelle :
		<a href="' . Router::url('member/index/slug:' . $v->loginmember) . '">' . $v->loginmember . '</a>
		
		</td>
		<td><a href="'.Router::url('member/approb_change_login/id:' . $v->id.'/stat:1').'">Accepter</a> / <a href="'.Router::url('member/approb_change_login/id:' . $v->id.'/stat:0').'">Refuser</a></td>
	</tr>';
	endforeach;

echo '</tbody></table>';

}
?>	
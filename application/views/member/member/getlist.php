<p>
<ul>
	<li>Membre enregistré: <?php echo $nbMember; ?></li>
<?php
if (count($doublon))
{
echo '<li><a href="'.Router::url('member/getmulticompte').'">Multi-compte détecté: '. count($doublon).'</a></li>';
}

?>
</ul>
</p>

<h3>Liste membre</h3>
<table class="zebra-striped">
<thead>
	<tr>
		<th>#</th>
		<th>Login</th>
		<th>E-Mail</th>
		<th>Date inscription</th>
		<th>Dernière connexion</th>
		<th>IP</th>
		<th>Validé</th>
	</tr>
</thead>
<tbody>
<?php foreach($listMember AS $k => $v):?>
	<tr>
		<td>
		<?php echo pagination($nbPage); ?>
		<a href="<?php echo Router::url('member/editother/id:'.$v->idmember); ?>"><?php echo $v->idmember; ?></a></td>
		<td><a href="<?php echo Router::url('member/index/slug:' . $v->loginmember); ?>"><?php echo $v->loginmember; ?></a></td>
		<td><a href="<?php echo Router::url('member/mailto/id:'.$v->idmember); ?>"><?php echo $v->mailmember; ?></a></td>
		<td><?php echo dates($v->firstactivitymember, 'fr_date'); ?></td>
		<td><?php echo dates($v->lastactivitymember, 'fr_date'); ?></td>
		<td><?php echo $v->ip; ?></td>
		<td><?php echo $v->validemember; ?></td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>


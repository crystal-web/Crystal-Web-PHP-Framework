<?php 
if (count($doublon))
{
?>
<h3>Multi-compte</h3>
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
		<th>Nb.</th>
	</tr>
</thead>
<tbody>

<?php foreach($doublon AS $k => $v):?>
	<tr>
		<td>
		<?php echo pagination($nbPage); ?>
		<a href="<?php echo Router::url('member/editother/id:'.$v->idmember); ?>"><?php echo $v->idmember; ?></a></td>
		<td><a href="<?php echo Router::url('member/index/slug:' . $v->loginmember); ?>"><?php echo $v->loginmember; ?></a></td>
		<td><a href="<?php echo Router::url('member/mailto/id:'.$v->idmember); ?>"><?php echo $v->mailmember; ?></a></td>
		<td><?php echo dates($v->firstactivitymember, 'fr_date'); ?></td>
		<td><?php echo dates($v->lastactivitymember, 'fr_date'); ?></td>
		<td><a href="<?php echo Router::url('member/getmulticompte/id:'.$v->idmember); ?>"><?php echo $v->ip; ?></a></td>
		<td><?php echo $v->validemember; ?></td>
		<td><?php echo $v->db; ?></td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php
}else {$this->mvc->Session->setFlash('Aucun multi-compte trouvé');} 
?>


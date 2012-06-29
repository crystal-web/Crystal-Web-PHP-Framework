				<!-- groupmember -->

	<?php echo pagination($nbPage); ?>
	<table>
		<thead>
			<tr>
				<th><a href="<?php echo Router::url('auth/manager/by:id/order:'.$order); ?>">#</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:login/order:'.$order); ?>">Login</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:mail/order:'.$order); ?>">E-Mail</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:subscribe/order:'.$order); ?>">Date inscription</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:connection/order:'.$order); ?>">Dernière connexion</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:ip/order:'.$order); ?>">IP</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:valide/order:'.$order); ?>">Validé</a></th>
				<th><a href="<?php echo Router::url('auth/manager/by:averto/order:'.$order); ?>">Avertiss.</a></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($memberList AS $k => $v): ?>
			<tr>
				<td><?php echo $v->idmember; ?></td>
				<td><?php echo $v->loginmember; ?></td>
				<td><?php echo $v->mailmember; ?></td>
				<td><?php echo dates($v->firstactivitymember, 'fr_date'); ?></td>
				<td><?php echo getRelativeTime($v->lastactivitymember); ?></td>
				<td><?php echo $v->ip; ?></td>
				<td><?php echo $v->validemember; ?></td>
				<td>Avertiss.</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo pagination($nbPage); ?>
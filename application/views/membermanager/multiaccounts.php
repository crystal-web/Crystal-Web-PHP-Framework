<?php
require 'tabs.inc.php';
 
if (count($doublon))
{
?>
	<div class="widget">
		<div class="widget-header">
			<h3>Liste des multi-comptes</h3>
		</div>
		<div class="widget-content">
			
			<?php echo pagination($nbPage); ?>
			<table class="zebra-striped bordered-table condensed-table">
			<thead>
				<tr>			
					<th>Pseudo</th>
					<th>Mail</th>
					<th>Enregistr&eacute;</th>
					<th>Dernière connexion</th>
					<th>IP</th>
					<th>Approbation</th>
					<th>Nb.</th>
					<th>Modifier</th>
				</tr>
			</thead>
			<tbody>
			
			<?php foreach($doublon AS $k => $v):?>
				<tr>
					<td><a href="<?php echo Router::url('member/index/slug:' . $v->loginmember); ?>"><?php echo $v->loginmember; ?></a></td>
					<td><a href="<?php echo Router::url('membermanager/mail/id:'.$v->idmember); ?>"><?php echo $v->mailmember; ?></a></td>
					<td><?php echo dates($v->firstactivitymember, 'fr_date'); ?></td>
					<td><?php echo dates($v->lastactivitymember, 'fr_date'); ?></td>
					<td><a href="<?php echo Router::url('membermanager/multiaccounts/id:'.$v->idmember); ?>"><?php echo $v->ip; ?></a></td>
					<td><?php echo $v->validemember; ?></td>
					<td><?php echo $v->db; ?></td>
					<td>
					<a href="<?php echo Router::url('membermanager/multiaccounts/id:'.$v->idmember); ?>" class="btn success"><?php echo i18n::get('look'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			<?php echo pagination($nbPage); ?>
		</div>
	</div>
<?php
}else {$this->mvc->Session->setFlash('Aucun multi-compte trouvé');} 
?>


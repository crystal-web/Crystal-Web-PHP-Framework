<?php if (count($liste)) {
loadFunction('bbcode'); ?>
<script type="text/javascript">
<!-- 
$(function() {
	$("table#sortTable").tablesorter({ sortList: [[1,0]] });
});
-->
</script>
<table class="table table-bordered table-striped" id="sortTable">
	<thead>
		<tr>
			<th>#</th>
			<th>Contact</th>
			<th>Motif</th>
			<th>Message</th>
			<th width="130">Date message</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($liste AS $k => $v): ?>
		<tr onclick="window.location = '<?php echo Router::url('contact/read/id:'.$v->id); ?>';">
			<td><?php echo $v->id; ?></td>
			<td><?php echo $v->firstname . ' ' . $v->lastname; ?></td>
			<td><?php echo $v->motif; ?>
			<td><?php echo truncate(stripBBcode($v->message), '256'); ?></td>
			<td><?php echo dates($v->time, 'fr_date'); ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php }
else
{
$this->mvc->Session->setFlash('Aucun message');
}
?>
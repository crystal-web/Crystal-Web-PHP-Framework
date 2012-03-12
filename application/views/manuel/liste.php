
<table class="zebra-striped" id="sortTableExample">
<thead>
	<tr>
		<th>Titre</th>
		<th>Auteur</th>
		<th>Date</th>
		<th>Categorie</th>
		<th>Hits</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php
foreach ($articleList AS $k => $v):
?>
	<tr>
		<td><?php echo $v->titre; ?></td>
		<td><?php echo $v->auteur; ?></td>
		<td><?php echo $v->date; ?></td>
		<td><?php echo $v->categorie; ?></td>
		<td><?php echo $v->hit; ?></td>
		<td>
			<a href="#"  data-controls-modal="delpost" data-backdrop="true" data-keyboard="true" onclick="delPost('<?php echo Router::url('article/admin_delpost/id:'.$v->id); ?>');"><img src="<?php echo __CDN; ?>/files/images/icons/eraser.png" alt="Editon"></a>
			<a href="<?php echo Router::url('article/admin_addpost/id:'.$v->id); ?>"><img src="<?php echo __CDN; ?>/files/images/icons/newspaper--pencil.png" alt="Suppression"></a>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>


<script type="text/javascript">
function delPost(postHref)
{
document.getElementById("del").href = postHref + '?token=<?php echo $token; ?>';
$('#modalTitle').text('Alerte...');
$('#modalText').html('<p>Supprimer l\'article ?</p>');
}


$(function() {
	$("table#sortTableExample").tablesorter({ sortList: [[1,0]] });
});
</script>
<div id="delpost" class="modal hide fade">
	<div class="modal-header">
		<a href="#" class="close">&times;</a>
		<h3 id="modalTitle"></h3>
	</div>
	
	<div class="modal-body" id="modalText"></div>
	<div class="modal-footer">
		<a href="#" class="btn" onclick="$('#delpost').modal('hide');">NON</a>
		<a href="#" id="del" class="btn danger">OUI</a>
	</div>
</div>

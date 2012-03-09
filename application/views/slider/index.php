<?php if (count($slider)) { ?>
<p>
<a href="<?php echo Router::url('sliderpop/pop'); ?>" class="btn primary">Ajouter</a>
</p>

<table class="bordered-table zebra-striped">
	<thead>
		<tr>
			<th>Info slider</th>
			<th width="100">Action</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($slider AS $k=>$v):
	$p = parse_url($v->link);
	$url = null;
	$url = $p['host'] . (iSset($p['path']) ? $p['path'] : '');
	
	?>
	
		<tr>
			<td>
				<h3><a href="<?php echo $v->image; ?>" onclick="window.open(this.href, 'pop25','menubar=no, status=no, scrollbars=yes, width=870, height=400');return false;"><?php echo stripcslashes($v->title); ?></a></h3>
				<p><?php echo stripcslashes($v->description); ?></p>
				<p><a href="<?php echo $v->link; ?>"><?php echo $url; ?></a></p>
			</td>
			<td>
				<?php $statut=null;
				$statutimg = ($v->active == 'y') ?  'status' : 'status-busy';
				echo '<a href="'.Router::url('sliderpop/id:'.$v->id.'/stat:'.( ($v->active == 'y') ? 0 : 1 ) ).'" title="Activer ou désactiver le slide" class="toolTip"><img src="'.__CDN . '/files/images/icons/'.$statutimg.'.png"></a>';
				?>

				<a href="#"  data-controls-modal="delslide" data-backdrop="true" data-keyboard="true" onclick="delslide('<?php echo Router::url('sliderpop/del/id:'.$v->id); ?>');" title="Supprimer le slide" class="toolTip">
					<img src="<?php echo __CDN; ?>/files/images/icons/eraser.png" alt="Suppression">
				</a>
				<a href="<?php echo Router::url('sliderpop/edit/id:'.$v->id); ?>" title="Editer le slide" class="toolTip">
					<img src="<?php echo __CDN; ?>/files/images/icons/newspaper--pencil.png" alt="Edition">
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<p>
<a href="<?php echo Router::url('sliderpop/pop'); ?>" class="btn primary">Ajouter</a>
</p>

<script type="text/javascript">
function delslide(postHref)
{
console.log('dele');
document.getElementById("del").href = postHref + '?token=<?php echo $this->mvc->Session->getToken(); ?>';
$('#modalTitle').text('Alerte...');
$('#modalText').html('<p>Supprimer le slider ?</p>');
}
</script>
<div id="delslide" class="modal hide fade">
	<div class="modal-header">
		<a href="#" class="close">&times;</a>
		<h3 id="modalTitle"></h3>
	</div>
	
	<div class="modal-body" id="modalText"></div>
	<div class="modal-footer">
		<a href="#" class="btn" onclick="$('#delslide').modal('hide');">NON</a>
		<a href="#" id="del" class="btn danger">OUI</a>
	</div>
</div>
<?php }
else
{
$this->mvc->Session->setFlash('Aucun slider pour le moment.<br><a href="' .  Router::url('sliderpop/pop') . '" class="btn primary">Ajouter</a>');
}?>

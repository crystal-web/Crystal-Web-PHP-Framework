<script type="text/javascript">
<!--
jQuery(function($){
	$('.del').live('click',function(e){
	e.preventDefault();
	
	var elem = $(this); 
		if(confirm('Voulez vous vraiment supprimer ce fichier ?')){
			$.getJSON(CW_PATH + "/mediamanager/rpc",{action:'delete',id:elem.attr('href')}, 
			function(json) {
		    	if (json.error)
		    	{
		    		alert(json.message);
		    	} else { window.open("<?php echo Router::url('mediamanager/browser'); ?>", "_self"); }
		    });
		}
	return false; 
	});
})
//-->
</script>
<?php $stat = alt_stat($fileaddr); ?>
<table class="condensed-table bordered-table zebra-striped">
<tr>
	<td>Download:</td>
	<td><a href="<?php echo __CW_PATH . '/media/upload/'.$fileinfo->folder . '/'. $fileinfo->name; ?>">Fichier</a> <?php echo _format_bytes(filesize($fileaddr)); ?></td>
</tr>
<tr>
	<td>Nom:</td>
	<td><?php echo $fileinfo->name; ?></td>
</tr>
<tr>
	<td>Type:</td>
	<td><a href="<?php echo Router::url('mediamanager/browser/type:' . trim($fileinfo->type, '/') . '/sub:' . trim($fileinfo->subType, '/') ); ?>"><?php echo $fileinfo->mime; ?></a></td>
</tr>


<tr>
	<td>Date de création:</td>
	<td><?php echo dates($stat['time']['ctime'], 'fr_datetime'); ?></td>
</tr>
<tr>
	<td>Dernière modification:</td>
	<td><?php echo dates($stat['time']['mtime'], 'fr_datetime'); ?></td>
</tr>
<tr>
	<td>Dernière accès:</td>
	<td><?php echo dates($stat['time']['atime'], 'fr_datetime'); ?></td>
</tr>


<tr>
	<td>Propriètaire (web):</td>
	<td><?php echo '<a href="' . Router::url('member/index/slug:' . clean($fileinfo->loginmember, 'slug')). '">'.clean($fileinfo->loginmember, 'slug').'</a>'; ?></td>
</tr>
<tr>
	<td>Propriètaire (unix):</td>
	<td><?php echo 'owner: ' . $stat['owner']['owner']['name'] . ' ('.$stat['owner']['fileowner'].')<br>group: ' . $stat['owner']['group']['name'] . ' ('.$stat['owner']['filegroup'].')'; ?></td>
</tr>
<tr>
	<td>Chmod:</td>
	<td><?php echo $stat['perms']['octal1'] . '<br>' .$stat['perms']['human']; ?></td>
</tr>
<tr>
<?php if ($stat['filetype']['is_writable']): 
	switch ($fileinfo->mime)
	{
		case 'text/html':
			echo '<td><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
			echo '<td><a href="' .  Router::url('mediamanager/edit/id:' . $fileinfo->id) . '">'.i18n::get('Edit file').'</a></td>';
		break;
		case 'text/plain':
			echo '<td><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
			echo '<td><a href="' .  Router::url('mediamanager/edit/id:' . $fileinfo->id) . '">'.i18n::get('Edit file').'</a></td>';
		break;
		case 'image/png':
			echo '<td><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
			echo '<td><a href="' .  Router::url('mediamanager/edit/id:' . $fileinfo->id) . '">'.i18n::get('Edit file').'</a></td>';
		break;
		case 'image/jpeg':
			echo '<td><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
			echo '<td><a href="' .  Router::url('mediamanager/edit/id:' . $fileinfo->id) . '">'.i18n::get('Edit file').'</a></td>';
		break;
		case 'image/jpg':
			echo '<td><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
			echo '<td><a href="' .  Router::url('mediamanager/edit/id:' . $fileinfo->id) . '">'.i18n::get('Edit file').'</a></td>';
			break;
		case 'image/gif':
			echo '<td><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
			echo '<td><a href="' .  Router::url('mediamanager/edit/id:' . $fileinfo->id) . '">'.i18n::get('Edit file').'</a></td>';
		break;
		default:
			echo '<td colspan="2"><a href="' . $fileinfo->id . '" class="del">'.i18n::get('Delete file').'</a></td>';
		break;
	}
	
	?>
<?php else: ?>
	<td colspan="2">Le fichier ne peut pas être détruit</td>
<?php endif; ?>
</tr>
</table>

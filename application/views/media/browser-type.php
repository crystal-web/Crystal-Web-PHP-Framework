<?php 
if (count($group))
{
	echo '<ul style="margin: 5px 0 5px 0;">';
	foreach($group AS $k => $d)
	{
		echo '<li style="display:inline;padding:5px;margin:5px;"><a href="' . Router::url('media/browser/type:' . $d->type . '/sub:' . $d->subType) . '">' . $d->subType . '</a> ('.$d->countSubType.')</li>';
	}
	
	echo '</ul>';
	
}

if (count($list))
{
	echo '<table class="condensed-table bordered-table zebra-striped">
		<tr>
			<th>filename</th>
			<th>filetype</th>
			<th>filesubtype</th>
			<th>filesize</th>
		</tr>';
	foreach($list AS $k => $d)
	{
		echo '<tr>
			<td><a href="' . __CW_PATH . '/media/' . $d->mime . '/' . $d->name .'">' . $d->name .'</a></td>
			<td><a href="' . Router::url('media/browser/type:' . $d->type) . '">' . $d->type . '</a></td>
			<td><a href="' . Router::url('media/browser/type:' . $d->type . '/sub:' . $d->subType) . '">'.$d->subType.'</a></td>
			<td>' . $d->filesize . '</td>
			</tr>';
	}
	echo '</table>';
	
}
?>
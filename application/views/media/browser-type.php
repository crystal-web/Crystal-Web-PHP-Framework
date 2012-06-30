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
		</tr>';
	foreach($list AS $k => $d)
	{
		//$d->subType = substr($d->subType, 1);
		echo '<tr>
			<td><a href="' . Router::url('media/fileinfo/id:' . $d->id) .'">' . $d->name .'</a></td>
			<td><a href="' . Router::url('media/browser/type:' . $d->type) . '">' . $d->type . '</a></td>
			<td><a href="' . Router::url('media/browser/type:' . $d->type . '/sub:' . $d->subType) . '">'.$d->subType.'</a></td>
			</tr>';
	}
	echo '</table>';
	
}
?>
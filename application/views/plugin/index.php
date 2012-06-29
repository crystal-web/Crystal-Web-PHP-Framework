<?php
if (count($pluginList)):
?>
<table>
	<thead>
		<tr>
			<th width="200">Plugin</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
<?php 
foreach($pluginList AS $k => $v): ?>
		<tr>
			<td>
				<?php echo isSet($v['info']['name']) ? $v['info']['name'] : 'Sans nom ('.$k.')'; ?><br>
				<?php echo isSet($v['info']['setting']) ? '<a href="' .Router::url('plugin/manager/slug:' . $k) . '">Manager</a>' . ' | ' : ''; ?> <?php 
				
				if ($v['enable'])
					echo 'Action : <a href="'.Router::url('plugin/slug:'.$k.'/stat:0').'">Désactiver</a>';
				else
					echo 'Action : <a href="'.Router::url('plugin/slug:'.$k.'/stat:1').'">Activer</a>'; ?>
			</td>
			<td>
				<p>
				<?php echo isSet($v['info']['description']) ? clean($v['info']['description'], 'str') : 'Aucun description'; ?><br>
				
				</p>
				Version <?php echo isSet($v['info']['version']) ? clean($v['info']['version'], 'str') : 'inconnu'; ?>	| 
				Par <?php $team = isSet($v['info']['team']) ? '(' . clean($v['info']['team'], 'str') . ')' : '';
				echo isSet($v['info']['author']) ? clean($v['info']['author'], 'str') . ' ' . $team : 'inconnu'; ?>

				<?php 
				if (isSet($v['info']['website']))
				{
				echo isURL($v['info']['website']) ? ' | <a href="'.$v['info']['website'].'">Aller sur le site du plugin</a>' : '';
				}
				?>
				
			</td>
		</tr>
	<?php
endforeach;//*/
?>
	</tbody>
</table>
<?php
else:
$this->mvc->Session->setFlash('Aucun plugin détécté');
endif;
?>
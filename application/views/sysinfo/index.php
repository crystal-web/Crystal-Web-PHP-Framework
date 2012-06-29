<?php
function kilobyte($size) {
	$size = (int) $size;
    $units = array(' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return array('size' => round($size, 2), 'unit' => $units[$i]);
}
?>
<h3>Information général:</h3>
<div class="well">
<table>
	<tr>
		<td>Uptime:</td>
		<td><?php echo $uptime; ?></td>
	</tr>
	<tr>
		<td>Horloge serveur:</td>
		<td><?php echo $time; ?></td>
	</tr>
	<tr>
		<td>Noyau:</td>
		<td><?php echo $kernel; ?></td>
	</tr>
	<tr>
		<td>Processeur:</td>
		<td><?php echo $cpu_info; ?></td>
	</tr>
	<tr>
		<td>Cache:</td>
		<td><?php echo $cache; ?></td>
	</tr>
	<tr>
		<td>Calcul par secondes:</td>
		<td><?php echo $bogomips; ?></td>
	</tr>
</table>
</div>

<h3>Partitions:</h3>
<div class="well">
<table class="zebra-striped">
<thead>
<tr>
	<th>Mount</th>
	<th>Size</th>
	<th>Free</th>
	<th>Used</th>
	<th style="width:150px;">Usage</th>
</tr>
</thead>
<tbody>
<?php
foreach($partition AS $k => $v): ?>
	<tr>
		<td>Drive: <?php echo $v['drive']; ?><br>
			Mount: <?php echo $v['mount']; ?>
		</td>
		<td><?php $s = kilobyte($v['size']); echo $s['size']  . $s['unit']; ?></td>
		<td><?php $s = kilobyte($v['used']); echo $s['size']  . $s['unit']; ?></td>
		<td><?php $s = kilobyte($v['avail']); echo $s['size'] . $s['unit']; ?></td>
		
		<td>
		
<div class="progress progress-info progress-striped active">
	<div style="position:absolute;text-align:center;width:150px;">
	<?php echo $v['percent']; ?>
	</div>
	<div class="bar" style="width: <?php echo $v['percent']; ?>"></div>
</div>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>


<h3>Mémoires:</h3>
<div class="well">
<table class="zebra-striped">
	<thead>
		<tr>
			<th colspan="3">Memoire <?php $s = kilobyte($memoire['total_mem']); echo $s['size']  . $s['unit']; ?></th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>Utilisé</td>
		<td><?php $s = kilobyte($memoire['used_mem']); echo $s['size']  . $s['unit']; ?></td>
		<td style="width:150px;">
			<div class="progress progress-info progress-striped active">
				<div style="position:absolute;text-align:center;width:150px;">
				<?php echo $memoire['percent_used']; ?>
				</div>
				<div class="bar" style="width: <?php echo $memoire['percent_used']; ?>"></div>
			</div>		
		</td>
	</tr>
	
	<tr>
		<td>Libre</td>
		<td><?php $s = kilobyte(($memoire['total_mem'] - $memoire['used_mem'])); echo $s['size']  . $s['unit']; ?></td>
		<td>
			<div class="progress progress-info progress-striped active">
				<div style="position:absolute;text-align:center;width:150px;">
				<?php echo $memoire['percent_free']; ?>
				</div>
				<div class="bar" style="width: <?php echo $memoire['percent_free']; ?>"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td>Mis en cache</td>
		<td><?php $s = kilobyte($memoire['total_cach']); echo $s['size']  . $s['unit']; ?></td>
		<td style="width:150px;">
			<div class="progress progress-info progress-striped active">
				<div style="position:absolute;text-align:center;width:150px;">
				<?php echo $memoire['percent_cach']; ?>
				</div>
				<div class="bar" style="width: <?php echo $memoire['percent_cach']; ?>"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td>Tampon</td>
		<td><?php $s = kilobyte($memoire['total_buff']); echo $s['size']  . $s['unit']; ?></td>
		<td style="width:150px;">
			<div class="progress progress-info progress-striped active">
				<div style="position:absolute;text-align:center;width:150px;">
				<?php echo $memoire['percent_buff']; ?>
				</div>
				<div class="bar" style="width: <?php echo $memoire['percent_buff']; ?>"></div>
			</div>
		</td>
	</tr>
	</tbody>
</table>
</div>

<h3>Memoire d'échange:</h3>
<div class="well">
<table class="zebra-striped">
<thead>
	<tr>
		<th colspan="3">Memoire dédié: <?php $s = kilobyte($memoire['total_swap']); echo $s['size']  . $s['unit']; ?></th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>Utilisé</td>
		<td><?php $s = kilobyte($memoire['used_swap']); echo $s['size']  . $s['unit']; ?></td>
		<td style="width:150px;">
			<div class="progress progress-info progress-striped active">
				<div style="position:absolute;text-align:center;width:150px;">
				<?php echo $memoire['percent_swap']; ?>
				</div>
				<div class="bar" style="width: <?php echo $memoire['percent_swap']; ?>"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td>Libre</td>
		<td><?php $s = kilobyte($memoire['free_swap']); echo $s['size']  . $s['unit']; ?></td>
		<td style="width:150px;">
			<div class="progress progress-info progress-striped active">
				<div style="position:absolute;text-align:center;width:150px;">
				<?php echo $memoire['percent_swap_free']; ?>
				</div>
				<div class="bar" style="width: <?php echo $memoire['percent_swap_free']; ?>"></div>
			</div>	
		</td>
	</tr>
</tbody>
</table>
</div>
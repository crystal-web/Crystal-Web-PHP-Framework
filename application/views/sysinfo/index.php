<?php
function kilobyte($size) {
	$size = (int) $size;
    $units = array(' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return array('size' => round($size, 2), 'unit' => $units[$i]);
}
$this->mvc->Page->setHeader('
<script type="text/javascript">
jQuery(function($){
	 setInterval(function() {       
		 $("#charge").load("' . Router::url('sysinfo') . '?get=cpu");	
	    }, 3000); 
	 setInterval(function() {       
		 $("#memoire").load("' . Router::url('sysinfo') . '?get=mem");	
	    }, 3000);
	 setInterval(function() {       
		 $("#swa").load("' . Router::url('sysinfo') . '?get=swa");	
	    }, 3000); 
})
</script>
');

//debug(exec("top -n1 | grep 'Cpu(s)'| awk '{print $5;}' | sed -e 's/id,//g'"));sleep(2);
?>

<div class="wiget">
	<div class="widget-header"><h3>En claire</h3></div>
	<div class="widget-content" style="height: 150px;">
	
		<div style="width:200px;height:150px;float: left;display:inline-block;padding: 0 10px 0 10px;">
			<h3>Mémoires</h3>
			<div id="memoire">
			<img alt="" src="http://chart.apis.google.com/chart?chl=<?php echo $memoire['percent_used']; ?>&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:<?php echo trim($memoire['percent_used'], '%'); ?>">
			</div>
		</div>
		
		<div style="width:200px;height:150px;float: left;display:inline-block;padding: 0 10px 0 10px;">
			<h3>Charge</h3>
			<div id="charge">
				<?php 
				$sys_ticks = trim($uptime);
				$sys_ticks = explode(' ', $sys_ticks);
				$uptimeCut = array();
				$uptimeCut['systime'] = (isSet($sys_ticks[0])) ? $sys_ticks[0] : 'UNDEFINED';
				$uptimeCut['days'] = (isSet($sys_ticks[2])) ? $sys_ticks[2] : 'UNDEFINED';
				$uptimeCut['hours'] = (isSet($sys_ticks[5])) ? trim($sys_ticks[5], ',') : 'UNDEFINED';
				
				$uptimeCut['avgnow'] = (isSet($sys_ticks[12])) ? trim($sys_ticks[12], ',') : 'UNDEFINED';
				$uptimeCut['avg5'] = (isSet($sys_ticks[13])) ? trim($sys_ticks[13], ',') : 'UNDEFINED';
				$uptimeCut['avg15'] = (isSet($sys_ticks[14])) ? trim($sys_ticks[14], ',') : 'UNDEFINED';
				?>
				<div style="padding: 30px 10px;
				  color: #dedede;
				  font-size: 55px;"><?php echo $uptimeCut['avgnow']; ?><span style="padding-left: 10px;
				  font-size: 26px;
				  color: #77AB10;">/<?php echo $total_cpu; ?></span></div>
				<div style="padding: 0 20px">
					<div style="float: left"><span><strong><?php echo $uptimeCut['avg5']; ?></strong></span><br />5 mins.</div>
					<div style="float: right"><span><strong><?php echo $uptimeCut['avg15']; ?></strong></span><br />15 mins.</div>
				</div>
			</div>
		</div>
		
		<div style="width:200px;height:150px;float: left;display:inline-block;"padding: 0 10px 0 10px;>
			<h3>Memoire d'échange</h3>
			<div id="swa">
			<!-- &chf=bg,s,EDEDED pour la couleur du fond -->
			<img alt="" src="http://chart.apis.google.com/chart?chl=<?php echo $memoire['percent_swap']; ?>&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:<?php echo trim($memoire['percent_swap'], '%'); ?>">
			</div>
		</div>
	</div> <!-- END widget-content -->
</div>

<h3>Information général:</h3>
<div class="well">
<table>
	<tr>
		<td>Uptime:</td>
		<td><?php
		//03:16:07 up 5 days, 2:03, 0 users, load average: 0.09, 0.03, 0.01
		 echo $uptime;
		 ?></td>
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
	<?php  if (isset($partition['primary']) ){ ?>
	<tr>
		<td>Espace Disc:</td>
		<td><?php
			$s = kilobyte($partition['primary']['used']); echo $s['size'] . $s['unit'];
			echo ' / ';
			$s = kilobyte($partition['primary']['size']); echo $s['size'] . $s['unit'];		
		?></td>
	</tr>	
	<?php } ?>
	
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
foreach($partition AS $k => $v):
if ($k !== 'primary'):
?>
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
<?php
endif;
endforeach; ?>
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
<?php
function kilobyte($size) {
	$size = (int) $size;
    $units = array(' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return array('size' => round($size, 2), 'unit' => $units[$i]);
}
$page = Page::getInstance();
$page->setHeader('
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
		
		<div style="width:200px;height:150px;float: left;display:inline-block;padding: 0 10px 0 10px;">
			<h3>Memoire d'échange</h3>
			<div id="swa">
			<!-- &chf=bg,s,EDEDED pour la couleur du fond -->
			<img alt="" src="http://chart.apis.google.com/chart?chl=<?php echo $memoire['percent_swap']; ?>&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:<?php echo trim($memoire['percent_swap'], '%'); ?>">
			</div>
		</div>
	</div> <!-- END widget-content -->
</div>


<ul class="tabs" style="padding-top:10px;">
  <li class="active"><a href="#default">M&eacute;moire</a></li>
  <li><a href="#apphp">Apache / PHP</a></li>
</ul>


<div class="pill-content">
	<div class="active" id="default">
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

	<?php $resultat = exec('who', $lines); 
	if (count($lines))
	{
		$s = (count($lines) > 1) ? 's' : NULL;
	?>
	<h3>Utilisateur<?php echo $s; ?> connect&eacute;<?php echo $s; ?> SSH:</h3>
	<div class="well">
	<?php
	 foreach($lines as $line){
			echo $line . '<br>';
	}
	?>
	</div>
	<?php } ?>

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
			<td><?php $s = kilobyte($v['avail']); echo $s['size']  . $s['unit']; ?></td>
			<td><?php $s = kilobyte($v['used']); echo $s['size'] . $s['unit']; ?></td>
			
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
	
	</div> <!-- #default -->
	<div id="apphp">
		<h3>Apache:</h3>
		<div class="well">
		<table class="zebra-striped">
		<thead>
			<tr>
				<th colspan="2">
				Apache Version: <?php echo $modulePHP['apache2handler']['Apache Version']; ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Server Administrator</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['Server Administrator']; ?></td>
			</tr>
			<tr>
				<td>User/Group</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['User/Group']; ?></td>
			</tr>
			<tr>
				<td>Max Requests</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['Max Requests']; ?></td>
			</tr>
			<tr>
				<td>Timeouts</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['Timeouts']; ?></td>
			</tr>
			<tr>
				<td>Virtual Server</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['Virtual Server']; ?></td>
			</tr>
			<tr>
				<td>Server Root</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['Server Root']; ?></td>
			</tr>
			<tr>
				<td>Loaded Modules</td>
				<td colspan="2"><?php echo $modulePHP['apache2handler']['Loaded Modules']; ?></td>
			</tr>
		</tbody>
		</table>
		</div>
		
		
		
		<h3>PHP:</h3>
		<div class="well">
		<table class="zebra-striped">
		<thead>
			<tr>
				<th colspan="2">
				PHP Version: <?php echo $modulePHP['Core']['PHP Version']; ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>allow_url_fopen</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['Core']['allow_url_fopen'][0]; ?></td>
			</tr>
			<tr>
				<td>allow_url_include</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['Core']['allow_url_include'][0]; ?></td>
			</tr>
			<tr>
				<td>always_populate_raw_post_data</td>
				<td colspan="2"><?php echo $modulePHP['Core']['always_populate_raw_post_data'][0]; ?></td>
			</tr>			
			<tr>
				<td>asp_tags</td>
				<td colspan="2"><?php echo $modulePHP['Core']['asp_tags'][0]; ?></td>
			</tr>
			<tr>
				<td>max_execution_time</td>
				<td colspan="2"><?php echo $modulePHP['Core']['max_execution_time'][0]; ?></td>
			</tr>
			<tr>
				<td>file_uploads</td>
				<td colspan="2"><?php echo $modulePHP['Core']['file_uploads'][0]; ?></td>
			</tr>
			<tr>
				<td>upload_max_filesize</td>
				<td colspan="2"><?php echo $modulePHP['Core']['upload_max_filesize'][0]; ?></td>
			</tr>
			<tr>
				<td>max_file_uploads</td>
				<td colspan="2"><?php echo $modulePHP['Core']['max_file_uploads'][0]; ?></td>
			</tr>
			<tr>
				<td>post_max_size</td>
				<td colspan="2"><?php echo $modulePHP['Core']['post_max_size'][0]; ?></td>
			</tr>
			<tr>
				<td>memory_limit</td>
				<td colspan="2"><?php echo $modulePHP['Core']['memory_limit'][0]; ?></td>
			</tr>
			
			<tr>
				<td>register_globals</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['register_globals'][0]; ?></td>
			</tr>
			<tr>
				<td>register_long_arrays</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['register_long_arrays'][0]; ?></td>
			</tr>			
			<tr>
				<td>expose_php</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['expose_php'][0]; ?></td>
			</tr>
			<tr>
				<td>magic_quotes_gpc</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['magic_quotes_gpc'][0]; ?></td>
			</tr>
			
			<tr>
				<td>magic_quotes_runtime</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['magic_quotes_runtime'][0]; ?></td>
			</tr>
			<tr>
				<td>magic_quotes_sybase</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['magic_quotes_sybase'][0]; ?></td>
			</tr>
			<tr>
				<td>html_errors</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['html_errors'][0]; ?></td>
			</tr>
			<tr>
				<td>ignore_repeated_errors</td>
				<td colspan="2"><?php echo $modulePHP['Core']['ignore_repeated_errors'][0]; ?></td>
			</tr>
			
			<tr>
				<td>disable_classes</td>
				<td colspan="2"><?php echo preg_replace('#,#', '<br>', $modulePHP['Core']['disable_classes'][0]); ?></td>
			</tr>
			<tr>
				<td>disable_functions</td>
				<td colspan="2"><?php echo preg_replace('#,#', '<br>', $modulePHP['Core']['disable_functions'][0]); ?></td>
			</tr>
			<tr>
				<td>display_errors</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['display_errors'][0]; ?></td>
			</tr>
			<tr>
				<td>display_startup_errors</td>
				<td colspan="2" title="Good if Off"><?php echo $modulePHP['Core']['display_startup_errors'][0]; ?></td>
			</tr>
		</tbody>
		</table>
		</div>
		
		
		<h3>Compl&eacute;ments:</h3>
		<div class="well">
		<table class="zebra-striped">
		<tbody>
			<tr>
				<td>FTP support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['ftp']['FTP support']; ?></td>
			</tr>
			
			<tr>
				<td>GetText Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gettext']['GetText Support']; ?></td>
			</tr>
			
			<tr>
				<td>GD Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['GD Support']; ?></td>
			</tr>
			<tr>
				<td>GD Version</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['GD Version']; ?></td>
			</tr>
			
			<tr>
				<td>FreeType Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['FreeType Support']; ?></td>
			</tr>
			<tr>
				<td>FreeType Version</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['FreeType Version']; ?></td>
			</tr>
			<tr>
				<td>T1Lib Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['T1Lib Support']; ?></td>
			</tr>
			<tr>
				<td>GIF Read Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['GIF Read Support']; ?></td>
			</tr>
			<tr>
				<td>GIF Create Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['GIF Create Support']; ?></td>
			</tr>			
			<tr>
				<td>JPEG Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['JPEG Support']; ?></td>
			</tr>	
			<tr>
				<td>libJPEG Version</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['libJPEG Version']; ?></td>
			</tr>
			<tr>
				<td>PNG Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['PNG Support']; ?></td>
			</tr>
			<tr>
				<td>libPNG Version</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['libPNG Version']; ?></td>
			</tr>
			<tr>
				<td>WBMP Support</td>
				<td colspan="2" title="good if this Off"><?php echo $modulePHP['gd']['WBMP Support']; ?></td>
			</tr>	
		</tbody>
		</table>
		</div>		
	</div>
</div> <!-- Pills -->
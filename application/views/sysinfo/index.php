<script type="text/javascript">
	function goPhpDoc(val, type) {
		var url;
		url = val.replace(/([_])/g,"-");
		window.open("http://php.net/manual/" + type + "." + url + ".php", '_blank');
  		window.focus();
  		return false;
	}
	function goApacheDoc(val) {
		window.open("http://httpd.apache.org/docs/2.2/mod/" + val + ".html", '_blank');
  		window.focus();
  		return false;
	}
	function getStatus() { 
		jQuery.getJSON("<?php echo Router::selfURL(); ?>/rpc",
		  {
		    format: "json"
		  },
		  function(data) {
		  	jQuery('#avgnow').html(data.sys.avg.avgnow);
		  	jQuery('#avg5').html(data.sys.avg.avg5);
		  	jQuery('#avg15').html(data.sys.avg.avg15);
		  	jQuery('#systime').html(data.sys.time);
		  	jQuery('#memoire').html('<img src="http://chart.apis.google.com/chart?chl=' + data.memory.percent_used_real + '%&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:' + data.memory.percent_used_real + '">');
		  	jQuery('#memoire_swap').html('<img src="http://chart.apis.google.com/chart?chl=' + data.memory.percent_swap + '%&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:' + data.memory.percent_swap + '">');
		});
		setTimeout(getStatus, 2500);
	}
	getStatus();
</script>
<?php
$acl = AccessControlList::getInstance();
function hightlight($str) {
	return $str;
}

function kilobyte($size) {
	$size = (int) $size;
    $units = array(' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return array('size' => round($size, 2), 'unit' => $units[$i]);
}
$page = Page::getInstance();

//debug(exec("top -n1 | grep 'Cpu(s)'| awk '{print $5;}' | sed -e 's/id,//g'"));sleep(2);
function diff($percent, $bar='success') {
	$percent = preg_replace('#%#', NULL, $percent);
	return ;'<div class="bar bar-'.$bar.'" style="width: ' . (100-$percent) . '%">';
}
?>
<table class="table no-footer">
	<tr>
		<td style="width:33%">
			<h3>Mémoires</h3>
			<div id="memoire" style="text-align: center;">
				<img alt="" src="http://chart.apis.google.com/chart?chl=0%&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:0">
			</div>
		</td>
		<td style="width:33%">
			<h3>Charge</h3>
			<div id="charge" style="text-align: center;">
				<?php 
				$sys_ticks = trim($uptime);
				$sys_ticks = explode(' ', $sys_ticks);
				$uptimeCut = array();
				$uptimeCut['systime'] = (isSet($sys_ticks[0])) ? $sys_ticks[0] : '0';
				$uptimeCut['days'] = (isSet($sys_ticks[2])) ? $sys_ticks[2] : '0';
				$uptimeCut['hours'] = (isSet($sys_ticks[5])) ? trim($sys_ticks[5], ',') : '0';
				
				$uptimeCut['avgnow'] = (isSet($sys_ticks[12])) ? trim($sys_ticks[12], ',') : '0';
				$uptimeCut['avg5'] = (isSet($sys_ticks[13])) ? trim($sys_ticks[13], ',') : '0';
				$uptimeCut['avg15'] = (isSet($sys_ticks[14])) ? trim($sys_ticks[14], ',') : '0';
				?>
				<div style="padding: 30px 10px;
				  color: #dedede;
				  font-size: 55px;">
				  <span id="avgnow">0</span>
				  	
				  <span style="padding-left: 10px;
				  font-size: 26px;
				  color: #77AB10;"> / 
				  	<span id="nb_cpu"><?php echo $total_cpu; ?></span>
				  </span>
				</div>
				<div style="padding: 0 20px">
					<div style="float: left">
						<span>
							<strong id="avg5">0</strong>
						</span><br />5 mins.</div>
					<div style="float: right">
						<span>
							<strong id="avg15">0</strong>
						</span><br />15 mins.</div>
				</div>
			</div>
		</td>
		<td style="width:33%">
			<h3>Memoire d'échange</h3> 
			<div id="memoire_swap" style="text-align: center;">
			<!-- &chf=bg,s,EDEDED pour la couleur du fond -->
				<img alt="" src="http://chart.apis.google.com/chart?chl=0%&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:0">
			</div>
		</td>
	</tr>

</table>



<ul class="nav nav-tabs tabs">
	<li class="active"><a href="#default" data-toggle="tab">Serveur</a></li>
	<?php
		echo '<li><a href="#install" data-toggle="tab">Infos site</a></li>';
		echo '<li><a href="#apphp" data-toggle="tab">Apache / PHP</a></li>';
		echo '<li><a href="#sql" data-toggle="tab">SQL</a></li>';
	?>
</ul>


<div class="tab-content">
	<div class="tab-pane" id="apphp">
		<h3>Apache:</h3>
		<table class="table table-striped table-bordered">
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
					<td style="width:50%;"><?php echo $modulePHP['apache2handler']['Server Administrator']; ?></td>
				</tr>
				<tr>
					<td>User/Group</td>
					<td><?php echo $modulePHP['apache2handler']['User/Group']; ?></td>
				</tr>
				<tr>
					<td>Max Requests</td>
					<td><?php echo $modulePHP['apache2handler']['Max Requests']; ?></td>
				</tr>
				<tr>
					<td>Timeouts</td>
					<td><?php echo $modulePHP['apache2handler']['Timeouts']; ?></td>
				</tr>
				<tr>
					<td>Virtual Server</td>
					<td><?php echo $modulePHP['apache2handler']['Virtual Server']; ?></td>
				</tr>
				<tr>
					<td>Server Root</td>
					<td><?php echo $modulePHP['apache2handler']['Server Root']; ?></td>
				</tr>
				<tr>
					<td colspan="2">Loaded Modules</br>
					<?php echo ($modulePHP['apache2handler']['Loaded Modules'] != 'no value') ? '<span class="badge warning" onclick="goApacheDoc(this.innerText);">' . preg_replace('# #', '</span> <span class="badge warning" onclick="goApacheDoc(this.innerText);">', $modulePHP['apache2handler']['Loaded Modules']) . '</span>' : '<span class="badge inverse">no value</span>'; ?>
					</td>
				</tr>
			</tbody>
		</table>		
		
		
		<h3>PHP:</h3>
		<table class="table table-striped table-bordered">
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
					<td  style="width:150px;"><a href="http://php.net/manual/filesystem.configuration.php#ini.allow-url-fopen" target="_blank"><?php echo ($modulePHP['Core']['allow_url_fopen'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>allow_url_include</td>
					<td ><a href="http://php.net/manual/filesystem.configuration.php#ini.allow-url-include" target="_blank"><?php echo ($modulePHP['Core']['allow_url_include'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>always_populate_raw_post_data</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.always-populate-raw-post-data" target="_blank"><?php echo ($modulePHP['Core']['always_populate_raw_post_data'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>			
				<tr>
					<td>asp_tags</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.asp-tags" target="_blank"><?php echo ($modulePHP['Core']['asp_tags'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>max_execution_time</td>
					<td><a href="http://php.net/manual/info.configuration.php#ini.max-execution-time" target="_blank"><span class="badge inverse"><?php echo $modulePHP['Core']['max_execution_time'][0]; ?></span></a></td>
				</tr>
				<tr>
					<td>file_uploads</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.file-uploads" target="_blank"><?php echo ($modulePHP['Core']['file_uploads'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>upload_max_filesize</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.core.php#ini.upload-max-filesize" target="_blank"><span class="badge inverse"><?php echo $modulePHP['Core']['upload_max_filesize'][0]; ?></span></a></td>
				</tr>
				<tr>
					<td>max_file_uploads</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.max-file-uploads" target="_blank"><span class="badge inverse"><?php echo $modulePHP['Core']['max_file_uploads'][0]; ?></span></a></td>
				</tr>
				<tr>
					<td>post_max_size</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.post-max-size" target="_blank"><span class="badge inverse"><?php echo $modulePHP['Core']['post_max_size'][0]; ?></span></a></td>
				</tr>
				<tr>
					<td>memory_limit</td>
					<td><a href="http://php.net/manual/ini.core.php#ini.memory-limit" target="_blank"><span class="badge inverse"><?php echo $modulePHP['Core']['memory_limit'][0]; ?></span></a></td>
				</tr>
				
				<tr>
					<td>register_globals</td>
					<td title="Good if Off"><a href="http://php.net/manual/ini.core.php#ini.register-globals" target="_blank"><?php echo (isset($modulePHP['Core']['register_globals'][0]) && $modulePHP['Core']['register_globals'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>register_long_arrays</td>
					<td title="Good if Off"><a href="http://php.net/manual/ini.core.php#ini.register-long-arrays" target="_blank"><?php echo (isset($modulePHP['Core']['register_long_arrays'][0]) && $modulePHP['Core']['register_long_arrays'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>			
				<tr>
					<td>expose_php</td>
					<td title="Good if Off"><a href="http://php.net/manual/ini.core.php#ini.expose-php" target="_blank"><?php echo (isset($modulePHP['Core']['expose_php'][0]) && $modulePHP['Core']['expose_php'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>magic_quotes_gpc</td>
					<td title="Good if Off"><a href="http://php.net/manual/info.configuration.php#ini.magic-quotes-gpc" target="_blank"><?php echo (isset($modulePHP['Core']['magic_quotes_gpc'][0]) && $modulePHP['Core']['magic_quotes_gpc'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				
				<tr>
					<td>magic_quotes_runtime</td>
					<td title="Good if Off"><a href="http://php.net/manual/info.configuration.php#ini.magic-quotes-runtime" target="_blank"><?php echo (isset($modulePHP['Core']['magic_quotes_runtime'][0]) && $modulePHP['Core']['magic_quotes_runtime'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>magic_quotes_sybase</td>
					<td title="Good if Off"><a href="http://php.net/manual/sybase.configuration.php#ini.magic-quotes-sybase" target="_blank"><?php echo (isset($modulePHP['Core']['magic_quotes_sybase'][0]) && $modulePHP['Core']['magic_quotes_sybase'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>html_errors</td>
					<td title="Good if Off"><a href="http://php.net/manual/errorfunc.configuration.php#ini.html-errors" target="_blank"><?php echo (isset($modulePHP['Core']['html_errors'][0]) && $modulePHP['Core']['html_errors'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>ignore_repeated_errors</td>
					<td><a href="http://php.net/manual/errorfunc.configuration.php#ini.ignore-repeated-errors" target="_blank"><?php echo (isset($modulePHP['Core']['ignore_repeated_errors'][0]) && $modulePHP['Core']['ignore_repeated_errors'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				
				<tr>
					<td colspan="2">disable_classes<br>
					<?php echo ($modulePHP['Core']['disable_classes'][0] != 'no value') ? '<span class="badge warning" onclick="goPhpDoc(this.innerText, \'class\');">' . preg_replace('#</span>#', '<br><span class="badge warning" onclick="goPhpDoc(this.innerText, \'class\');">', $modulePHP['Core']['disable_classes'][0]) . '</span>' : '<span class="badge inverse">no value</span>'; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">disable_functions<br>
					<?php echo ($modulePHP['Core']['disable_functions'][0] != 'no value') ? '<span class="badge warning" onclick="goPhpDoc(this.innerText, \'function\');">' . preg_replace('#,#', '</span> <span class="badge warning" onclick="goPhpDoc(this.innerText, \'function\');">', $modulePHP['Core']['disable_functions'][0]) . '</span>' : '<span class="badge inverse">no value</span>'; ?>
					</td>
				</tr>
				<tr>
					<td>display_errors</td>
					<td title="Good if Off"><a href="http://php.net/manual/errorfunc.configuration.php#ini.display-errors" target="_blank"><?php echo (isset($modulePHP['Core']['display_errors'][0]) && $modulePHP['Core']['display_errors'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
				<tr>
					<td>display_startup_errors</td>
					<td title="Good if Off"><a href="http://php.net/manual/errorfunc.configuration.php#ini.display-startup-errors" target="_blank"><?php echo (isset($modulePHP['Core']['display_startup_errors'][0]) && $modulePHP['Core']['display_startup_errors'][0] == 'On') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></a></td>
				</tr>
			</tbody>
		</table>
	
		
		
		<h3>Compl&eacute;ments:</h3>
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td>FTP support</td>
					<td  style="width:150px;"><?php echo (isset($modulePHP['ftp']['FTP support']) && $modulePHP['ftp']['FTP support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				
				<tr>
					<td>GetText Support</td>
					<td ><?php echo (isset($modulePHP['gettext']['GetText Support']) && $modulePHP['gettext']['GetText Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				
				<tr>
					<td>GD Support</td>
					<td ><?php echo (isset($modulePHP['gd']['GD Support']) && $modulePHP['gd']['GD Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				<tr>
					<td>GD Version</td>
					<td ><span class="badge inverse"><?php echo (isset($modulePHP['gd']['GD Version'])) ? $modulePHP['gd']['GD Version'] : 'non pris en charge'; ?></span></td>
				</tr>
				
				<tr>
					<td>FreeType Support</td>
					<td ><?php echo (isset($modulePHP['gd']['FreeType Support']) && $modulePHP['gd']['FreeType Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				<tr>
					<td>FreeType Version</td>
					<td ><span class="badge inverse"><?php echo $modulePHP['gd']['FreeType Version']; ?></span></td>
				</tr>
				<tr>
					<td>T1Lib Support</td>
					<td ><?php echo (isset($modulePHP['gd']['T1Lib Support']) && $modulePHP['gd']['T1Lib Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				<tr>
					<td>GIF Read Support</td>
					<td ><?php echo (isset($modulePHP['gd']['GIF Read Support']) && $modulePHP['gd']['GIF Read Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				<tr>
					<td>GIF Create Support</td>
					<td ><?php echo (isset($modulePHP['gd']['GIF Create Support']) && $modulePHP['gd']['GIF Create Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>			
				<tr>
					<td>JPEG Support</td>
					<td ><?php echo (isset($modulePHP['gd']['JPEG Support']) && $modulePHP['gd']['JPEG Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>	
				<tr>
					<td>libJPEG Version</td>
					<td ><span class="badge inverse"><?php echo (isset($modulePHP['gd']['libJPEG Version'])) ? $modulePHP['gd']['libJPEG Version'] : 'non pris en charge'; ?></span></td>
				</tr>
				<tr>
					<td>PNG Support</td>
					<td ><?php echo (isset($modulePHP['gd']['PNG Support']) && $modulePHP['gd']['PNG Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>
				<tr>
					<td>libPNG Version</td>
					<td ><span class="badge inverse"><?php echo isset($modulePHP['gd']['libPNG Version']) ? $modulePHP['gd']['libPNG Version'] : 'non pris en charge'; ?></span></td>
				</tr>
				<tr>
					<td>WBMP Support</td>
					<td ><?php echo (isset($modulePHP['gd']['WBMP Support']) && $modulePHP['gd']['WBMP Support'] == 'enabled') ? '<span class="badge success">On</span>' : '<span class="badge danger">off</span>'; ?></td>
				</tr>	
			</tbody>
		</table>	
	</div>


	<div class="tab-pane" id="sql">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th colspan="2">Variable_name</th>
				</tr>
			</thead>
			<tbody>
			<?php
			for($i=0;$i<count($sqlVar);$i++) {
                $sqlVar[$i]->Value = explode(',', $sqlVar[$i]->Value);
                $sqlVar[$i]->Value = implode(',<br>', $sqlVar[$i]->Value);
				echo '<tr><td>' . $sqlVar[$i]->Variable_name . '</td><td style="width: 33%;">' . $sqlVar[$i]->Value . '</td></tr>';
			}
			?>
			</tbody>
		</table>
	</div>


	<div class="tab-pane" id="install">
		<div class="headline">
			<h3>Information sur les r&eacute;pertoires</h3>
		</div>
		<table class="table table-striped table-bordered">
			<tr>
				<td class="span3">R&eacute;pertoire d'installation</td>
				<td class="span9"><?php echo __APP_PATH; ?></td>
			</tr>
			<tr>
				<td>R&eacute;pertoire publique</td>
				<td><?php echo __SITE_PATH; ?></td>
			</tr>
			<tr>
				<td>R&eacute;pertoire programme</td>
				<td><?php echo __APP_PATH; ?></td>
			</tr>
		</table>
		
		<div class="headline">
			<h3>Mode d'&eacute;criture des dossiers</h3>
		</div>
		<table class="table table-striped table-bordered">
<?php
	$readdir = function($dir) {
		if ($handle = opendir($dir)) {
		    /* Ceci est la façon correcte de traverser un dossier. */
		    while (false !== ($entry = readdir($handle))) {
		        $dirinfo = @alt_stat($dir.DS.$entry);

				if (isset(
						$dirinfo['perms'], $dirinfo['owner'],
						$dirinfo['filetype'],
						$dirinfo['file']
					) && (
						$dirinfo['file']['basename'] != '..' && 
						$dirinfo['file']['basename'] != '.' && 
						$dirinfo['filetype']['type'] == 'dir'
					)) {
					echo '<tr>' . 
						'<td>' .
							$dirinfo['file']['realpath'] . 
						'</td>' .
						'<td style="width: 33%;">' .
							'<ul class="unstyled">'.
								'<li>'.
									'Propri&eacute;taire: ' . 
									$dirinfo['owner']['owner']['name'] . ':' . $dirinfo['owner']['group']['name'] .
								'</li>' . 
								'<li>' . 
									'Droit: ' . 
									$dirinfo['perms']['octal2'] .
								'</li>' . 
							'</ul>' .
						'</td>' .  
					'</tr>';
				}
		    }
		    closedir($handle);
		}
	};
	
	$readdir(__APP_PATH);
	$readdir(__SITE_PATH);
  ?>
			</tr>
		</table>
	</div> <!-- #install -->
	
	
	
	<div class="tab-pane active" id="default">
	<h3>Information général:</h3>
	<table class="table table-striped table-bordered">
		<tr>
			<td style="width: 146px;">Uptime:</td>
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
				$used_percent = round( $partition['primary']['used'] / $partition['primary']['size'] * 100 );
				?>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $used_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $used_percent.'%'; ?>;"></div>
                    <span class="progress-text"><?php
                        $s = kilobyte($partition['primary']['used']); echo $s['size'] . $s['unit'];
                        echo ' / ';
                        $s = kilobyte($partition['primary']['size']); echo $s['size'] . $s['unit'];
                        echo ' (' . $used_percent . '%)';
                        ?>
                    </span>
                </div>
			</td>
		</tr>	
		<?php } ?>
	</table>


	<?php
	$resultat = exec('who', $lines); 
		if (count($lines)) {
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
		<?php
        }
	?>
	<h3>Partitions:</h3>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th style="width: 146px;">Mount</th>
					<th>Usage</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($partition AS $k => $v):
			if ($k !== 'primary'):
			?>
				<tr>
					<td style="width: 146px;">
						Drive: <?php echo $v['drive']; ?><br>
						Mount: <?php echo $v['mount']; ?>
					</td>
					<td>
						<?php
						$used_percent = round( $v['used'] / $v['size'] * 100 );
						?>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $used_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $used_percent.'%'; ?>;"></div>
                            <span class="progress-text"><?php
                                $s = kilobyte($v['used']); echo $s['size'] . $s['unit'];
                                echo ' / ';
                                $s = kilobyte($v['size']); echo $s['size'] . $s['unit'];
                                echo '(' . $used_percent . '%)';
                                ?>
                            </span>
                        </div>
					</td>
				</tr>
			<?php
			endif;
			endforeach; ?>
			</tbody>
		</table>


	<h3>Mémoires:</h3>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th colspan="3">Memoire <?php $s = kilobyte($memoire['total_mem']); echo $s['size']  . $s['unit']; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 146px;">
						Utilis&eacute;: <?php $s = kilobyte($memoire['real_mem_use']); echo $s['size']  . $s['unit']; ?><br>
                        Libre: <?php $s = kilobyte($memoire['real_mem_free']); echo $s['size']  . $s['unit']; ?>
					</td>
					<td>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo preg_replace('#%#', '', $memoire['percent_free_real']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $memoire['percent_free_real']; ?>;"></div>
                            <span class="progress-text"><?php
                                echo $s['size']  . $s['unit'];
                                echo '(' . $memoire['percent_free_real'] . ')';
                                ?>
                            </span>
                        </div>
					</td>
				</tr>
			</tbody>
		</table>

	</div> <!-- #default -->
</div> <!-- Pills -->
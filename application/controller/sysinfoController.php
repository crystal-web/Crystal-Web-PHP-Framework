<?php

Class sysinfoController extends Controller {

public function index(){

$this->mvc->Page->setPageTitle('Information serveur ');
if (!$this->mvc->Acl->isAllowed()){Router::redirect();}

if (PHP_OS != 'Linux'){$this->mvc->Session->setFlash('SysInfo fonctionne uniquement sur un serveur Linux', 'warning'); Router::redirect();}

$uptime = exec("uptime");



/***************************************
*	Information général
***************************************/
// Info systeme

$uname = explode(" ", exec("uname -a"), 5);


$total_cpu = 0;
$cpuinfo = file("/proc/cpuinfo");


for ($i = 0; $i < count($cpuinfo); $i++) {
$arr = explode(":", $cpuinfo[$i]);
$item = (isSet($arr[0])) ? $arr[0]: NULL;
$data = (isSet($arr[1])) ? $arr[1]: NULL;


		$item = chop($item);
		$data = chop($data);
		if ($item == "processor") {
				$total_cpu++;
				$cpu_info = $total_cpu;
		}
		if ($item == "vendor_id") { $cpu_info .= $data; }
		if ($item == "model name") { $cpu_info .= $data; }
		if ($item == "cpu MHz") {
				$cpu_info .= " " . floor($data);
				$found_cpu = "yes";
		}
		if ($item == "cache size") {$this->mvc->Template->cache = $data;}
		if ($item == "bogomips") { $this->mvc->Template->bogomips = $data;}
}
if($found_cpu != "yes") { $cpu_info .= " <b>inconnu</b>"; }
$cpu_info .= " MHz Processor(s)\n";
$this->mvc->Template->cpu_info = $cpu_info;


/***************************************
*	Information sur la mémoire
***************************************/
$total_mem = $free_mem = $total_swap = $buffer_mem = $cache_mem = $shared_mem = NULL;
$meminfo = file("/proc/meminfo");

foreach ($meminfo AS $k => $v)
{
	list($item, $data) = explode(":", $v, 2);
	$item = chop($item);
	$data = chop($data);
	if ($item == "MemTotal") { $total_mem =$data;	}
	if ($item == "MemFree") { $free_mem = $data; }
	if ($item == "SwapTotal") { $total_swap = $data; }
	if ($item == "SwapFree") { $free_swap = $data; }
	if ($item == "Buffers") { $buffer_mem = $data; }
	if ($item == "Cached") { $cache_mem = $data; }
	if ($item == "MemShared") {$shared_mem = $data; }
}
$memoire = array();
$memoire['total_mem'] = $total_mem;
$memoire['used_mem'] = ( $total_mem - $free_mem );
$memoire['total_swap'] = $total_swap;
$memoire['used_swap'] = ( $total_swap - $free_swap );
$memoire['free_swap'] = $free_swap ;

$memoire['total_buff'] = $buffer_mem;
$memoire['total_cach'] = $cache_mem;

$memoire['percent_free'] = round( $free_mem / $total_mem * 100 ).'%';
$memoire['percent_used'] = round( $memoire['used_mem'] / $total_mem * 100 ).'%';
$memoire['percent_buff'] = round( $buffer_mem / $total_mem * 100 ).'%';
$memoire['percent_cach'] = round( $cache_mem / $total_mem * 100 ).'%';
$memoire['percent_shar'] = round( $shared_mem / $total_mem * 100 ).'%';

$memoire['percent_swap'] = round( ( $total_swap - $free_swap ) / $total_swap * 100 ).'%';
$memoire['percent_swap_free'] = round( $free_swap / $total_swap * 100 ).'%';

// Template
$this->mvc->Template->memoire = $memoire;


/***************************************
*	Partitionnage du système
***************************************/
exec ("df", $x);
$percent_part = $drive = $size = $used = $avail = $percent = $mount = NULL;
$partition = array();
foreach($x AS $k => $v)
{
    $v = preg_replace('#\s+#', '|', $v);
	list($drive, $size, $used, $avail, $percent, $mount) = explode("|", $v);//*/
	$percent_part = str_replace( "%", "", $percent );

	if ($mount === "/")
	{
		$partition['primary'] = array(
		'drive'		=> $drive,
		'size'		=> $size,
		'used'		=> $used,
		'avail'		=> $avail,
		'percent'	=> $percent,
		'mount'		=> $mount,
		'partPerc'	=> $percent_part
		);
	}
$partition[] = array(
	'drive'		=> $drive,
	'size'		=> $size,
	'used'		=> $used,
	'avail'		=> $avail,
	'percent'	=> $percent,
	'mount'		=> $mount,
	'partPerc'	=> $percent_part
	);
}
unset($partition[0]);

// Template


if (isset($_GET['get']))
{
	switch ($_GET['get'])
	{
		case 'cpu':
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
		<?php 
		break;
		case 'mem':
		echo '<img alt="" src="http://chart.apis.google.com/chart?chl=' . $memoire['percent_used'] . '&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:' . trim($memoire['percent_used'], '%') .'">';
		break;
		case 'swa':
		echo '<img alt="" src="http://chart.apis.google.com/chart?chl=' . $memoire['percent_swap'] . '&chs=200x110&cht=gm&chco=77AB10,FFFF00|FF0000&chd=t:' . trim($memoire['percent_swap'], '%') .'">';
		break;
	}
	die;
}


$this->mvc->Template->uptime = $uptime;
$this->mvc->Template->time = (exec("date"));
$this->mvc->Template->system	= (isSet($uname[0])) ? $uname[0] : 'Inconnu';
$this->mvc->Template->host		= (isSet($uname[1])) ? $uname[1] : 'Inconnu';
$this->mvc->Template->kernel	= (isSet($uname[2])) ? $uname[2] : 'Inconnu';
$this->mvc->Template->total_cpu = $total_cpu;
$this->mvc->Template->partition = $partition;
$this->mvc->Template->show('sysinfo/index');

}

}
?>

<?php

Class sysinfoController extends Controller {

    public function index()
    {
		$page = Page::getInstance();
		$acl = AccessControlList::getInstance(); 
		$session = Session::getInstance();
		$template = Template::getInstance();
		
        $page->setPageTitle('Information serveur ');
        if (!$acl->isAllowed()){Router::redirect();}
        
        
        if (PHP_OS != 'Linux'){
        	$session->setFlash('SysInfo fonctionne uniquement sur un serveur Linux', 'warning');
        	Router::redirect();
		}
        
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
        		if ($item == "cache size") { $template->cache = $data; }
        		if ($item == "bogomips") { $template->bogomips = $data; }
        }
        if($found_cpu != "yes") { $cpu_info .= " <b>inconnu</b>"; }
        $cpu_info .= " MHz Processor(s)\n";
		
        $template->cpu_info = $cpu_info;
        
        
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
        
        $memoire['percent_swap'] = ($total_swap > 0) ? round( ( $total_swap - $free_swap ) / $total_swap * 100 ).'%' : '0%';
        $memoire['percent_swap_free'] = ($total_swap > 0) ? round( $free_swap / $total_swap * 100 ).'%' : '0%';
        
        // Template
		$template->memoire = $memoire;
        
        
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
        		$uptimeCut['hours'] = (isSet($sys_ticks[5])) ? trim($sys_ticks[5], ', ') : 'UNDEFINED';
        
        		if (preg_match('#([0-9]+\.[0-9]+), ([0-9]+\.[0-9]+), ([0-9]+\.[0-9]+)#', $uptime, $avarege))
        		{
        			$uptimeCut['avgnow'] = (isSet($avarege[1])) ? trim($avarege[1]) : 'UNDEFINED';
        			$uptimeCut['avg5'] = (isSet($avarege[2])) ? trim($avarege[2]) : 'UNDEFINED';
        			$uptimeCut['avg15'] = (isSet($avarege[3])) ? trim($avarege[3]) : 'UNDEFINED';			
        		}
        		else {
        			$uptimeCut['avgnow'] = (isSet($sys_ticks[12])) ? trim($sys_ticks[12], ', ') : 'UNDEFINED';
        			$uptimeCut['avg5'] = (isSet($sys_ticks[13])) ? trim($sys_ticks[13], ', ') : 'UNDEFINED';
        			$uptimeCut['avg15'] = (isSet($sys_ticks[14])) ? trim($sys_ticks[14], ', ') : 'UNDEFINED';		
        		}
        	
        
        		
        
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
        
        /*** Memoire ***/
        $template->uptime = $uptime;
        $template->time = (exec("date"));
        $template->system	= (isSet($uname[0])) ? $uname[0] : 'Inconnu';
        $template->host		= (isSet($uname[1])) ? $uname[1] : 'Inconnu';
        $template->kernel	= (isSet($uname[2])) ? $uname[2] : 'Inconnu';
        $template->total_cpu = $total_cpu;
        $template->partition = $partition;
		
		/*** Module ***/
		$mPhp = new PHPmodule();
		$template->modulePHP = $mPhp->parsePHPModules();
        $template->show('sysinfo/index');
    
    }


    public function netstat()
    {
    	$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		
        if (!$acl->isAllowed()){Router::redirect();}
        if (isSet($_GET['rpc']))
        {
            $page->setLayout('empty');
            $output = NULL;
            $linen=0;
            echo '<pre>';
            $resultat = exec('netstat -tn', $lines);
             foreach($lines as $line){
                    $output.=$line . PHP_EOL;
                 $linen++;
             }
            
            $s = ($linen>1) ? 's' : '';
            echo number_format($linen) . ' ligne' . $s .' re&ccedil;ue' . $s . PHP_EOL . $output . '</pre>';die;
        }

        
        $page->setPageTitle('NetStat')->setBreadcrumb('sysinfo', 'Information systeme');
        
     ?>
     <script>
    
    $(function(){refresh();});
    function refresh()
    {
        
        
            $.ajax({
            url: "?rpc=x", 
            ifModified:true,
            success: function(content){
                    if (content.length)
                    {
                        $('#log').html('<div id="consoleLog-liste">' + content + '</div>');
       
                    }
                    setTimeout(refresh, 5000);
                }
            });
    }
     </script>
     <div class="widget">
         <div class="widget-content" id="log">Patientez....</div>
     </div>
     
     <?php
    }
}
?>

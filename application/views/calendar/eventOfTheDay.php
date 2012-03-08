<style type="text/css">
.cal_heure {
width: 35px;
}
.cal_heure strong{
font-weight: bold;
font-size:150%;
}
</style>
<script type="text/javascript">
function getThis(id)
{
$('html, body').animate({scrollTop:$('#'+id).position().top-40}, 'slow');
}
</script>
<h3 id="div_id">
<?php
$count = count($dateEvent);

if ($count>0)
{
$firstEvent = array_keys($dateEvent);
$firstEvent = preg_replace('#:#','', $firstEvent[0]);
$s = ($count>1) ? 's' : null;
//'.Router::url('calendar/event/year:'.$year.'/month:'.$month.'/day:' . $day . '').'
echo '<a href="#'.$firstEvent.'" onclick="getThis(\''.$firstEvent.'\');return false;">'.$count . ' &eacute;venement'.$s.'</a>';
}
else
{
echo 'Aucun &eacute;venement';
}
?>
</h3>


<?php

$prevMonth = ($month-1>0) ? $month-1 : 12;
$prevYear = ($prevMonth==12) ? $year-1: $year;
$prevDay=1;

$nextMonth = ($month+1!=13) ? $month+1 : 1;
$nextYear = ($nextMonth==1) ? $year+1: $year;
$nextDay=1;

$currentTime = mktime(0,0,0,$month,$day,$year);
$prevDayTime = date('\y\e\a\r\:Y\/\m\o\n\t\h\:n\/\d\a\y\:j', $currentTime - 86400);
$nextDayTime = date('\y\e\a\r\:Y\/\m\o\n\t\h\:n\/\d\a\y\:j', $currentTime + 86400);
?>
<div class="clearfix cal_nav">
	<div class="left">
	<a href="<?php echo Router::url('calendar/event/'.$prevDayTime); ?>" class="btn info">Journ&eacute;e pr&eacute;c&eacute;dante</a>
	</div>

	<div class="right">
	<a href="<?php echo Router::url('calendar/event/'.$nextDayTime); ?>" class="btn info">Journ&eacute;e suivante</a>
	</div>
</div>

<table class="zebra-striped">
	<?php
$cle = array_keys($dateEvent);
$cleNo=0;
for($i=0; $i<24; $i++)
{

	if (isSet($dateEvent[$i.':0']))
	{
	$cleNo++;
	$next = (isSet($cle[$cleNo])) ? ' onclick="getThis(\''.preg_replace('#:#','', $cle[$cleNo]).'\');return false;"' : '';

	echo '<tr>
		<td class="cal_heure" id="'.$i.'0"><strong>'.$i.'</strong>:00</td>
		<td'.$next.'>
		'.stripcslashes($dateEvent[$i.':0']).'
		</td>
	</tr>';
	}
	else
	{
	echo '<tr>
		<td class="cal_heure" id="'.$i.'0"><strong>'.$i.'</strong>:00</td>
		<td>
		&nbsp;&nbsp;
		</td>
	</tr>';
	}
	
	
	if (isSet($dateEvent[$i.':30']))
	{
	$cleNo++;
	$next = (isSet($cle[$cleNo])) ? ' onclick="getThis(\''.preg_replace('#:#','', $cle[$cleNo]).'\');return false;"' : '';
	echo '<tr>
		<td class="cal_heure" id="'.$i.'30">30</td>
		<td'.$next.'>
		'.stripcslashes($dateEvent[$i.':30']).'
		</td>
	</tr>';
	}
	else
	{
	echo '<tr>
		<td class="cal_heure" id="'.$i.'30">&nbsp;&nbsp;30</td>
		<td>
		&nbsp;&nbsp;
		</td>
	</tr>';
	}

}

?>

</table>
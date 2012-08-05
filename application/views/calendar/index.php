<?php
$this->mvc->Page->setHeader("<style type=\"text/css\">.bubble em {    display:none; text-align: left;}.bubble:hover {opacity:1;border: 0;position: relative;z-index: 500;text-decoration:none;}.bubble:hover em {font-style: normal;display: block;position: absolute;top: 32px;left: 4px;padding: 5px;color: black;border: 1px solid #BBB;background: #FFC;width: 170px;}.bubble:hover em span {position: absolute;top: -7px;left: 15px;height: 7px;width: 11px;background: transparent url(http://service.crystal-web.org/aide_forum/date/image-infobulle.gif);margin:0;padding: 0;border: 0;}.cal_calendrier{border:1px solid black;padding:1px;background-color:#F0F0F0;width:100%;margin:auto;height:100%;font-weight: bold;}.cal_calendrier th{border:1px solid black;/*background-color:#7db4ff;*/background-color:#E0E0E0;font-size:125%;}.cal_calendrier td{border:1px solid black;/*background-color:#92c0ff;*/background-color:#ffffff;text-align:center;}.cal_aujourdhui{color:#ff0000;font-weight: bolder;background-color:#7db4ff !important;}.cal_jours_av_ap{color:#5a779e; background-color:#E1EAFB !important;}.cal_jours_av{color:#5a779e; background-color:#F2F2F2 !important;}.cal_calendrier td, .cal_calendrier th{opacity:0.7;text-align: center;}.cal_j_semaines{background-color:#7F7F7F !important;}.cal_event{background-color: #416DFF !important;}.cal_nav{padding-bottom: 40px;}.cal_calendrier a, a:hover{color:#084268;font-weight:bold;}</style>");

$jour = $day;//date('j');			//Jour du mois sans les zeros initiaux	1 a 31
$numJour = date('w');		//Jour de la semaine au format numerique	0 (pour dimanche) a 6 (pour samedi)
$mois = $month;//date('n');			//Mois sans les zeros initiaux	1 a 12
$annee = $year;//date('Y');			//Annee sur 4 chiffres	Exemples : 1999 ou 2003

$prevMonth = ($month-1>0) ? $month-1 : 12;
$prevYear = ($prevMonth==12) ? $year-1: $year;

$nextMonth = ($month+1!=13) ? $month+1 : 1;
$nextYear = ($nextMonth==1) ? $year+1: $year;
?>
<div class="clearfix cal_nav">
	<div class="left">
	<a href="<?php echo Router::url('calendar/year:'.$prevYear.'/month:'.$prevMonth); ?>" class="btn info">Mois pr&eacute;c&eacute;dant</a>
	</div>

	<div class="right">
	<a href="<?php echo Router::url('calendar/year:'.$nextYear.'/month:'.$nextMonth); ?>" class="btn info">Mois suivant</a>
	</div>
</div>
<?php
$jour = $day;//date('j');			//Jour du mois sans les zeros initiaux	1 a 31
$numJour = date('w');		//Jour de la semaine au format numerique	0 (pour dimanche) a 6 (pour samedi)
$mois = $month;//date('n');			//Mois sans les zeros initiaux	1 a 12
$annee = $year;//date('Y');			//Annee sur 4 chiffres	Exemples : 1999 ou 2003

$joursParMois = array(31,28,31,30,31,30,31,31,30,31,30,31);
	$joursParMois[1] = cal_days_in_month(CAL_GREGORIAN, 2, $annee); // Pour fevrier xD

	$num = ($mois-2 > 0) ? $mois-2 : 11;
$nbJourSeMoisPrecedent = $joursParMois[$num];
$nbJourSeMois = $joursParMois[$mois-1];


$firstDay = date('w', mktime(0, 0, 0, $mois, 1, $annee));

$jourTraiter = 0;

echo '<table class="cal_calendrier">
	<tbody id="cal_body">
		<tr class="cal_j_semaines">
			<th>Dim</th>
			<th>Lun</th>
			<th>Mar</th>
			<th>Mer</th>
			<th>Jeu</th>
			<th>Ven</th>
			<th>Sam</th>
		</tr>
		<tr>';


/* Les jours du mois passe */
for($i=1; $i<=$firstDay; $i++)
{
if ($jourTraiter == 0){echo '<tr>';}
echo '<td class="cal_jours_av_ap">
<a href="' . Router::url('calendar/event/year:'.$prevYear.'/month:'.$prevMonth.'/day:'.($joursParMois[$mois-1]-$firstDay)) . '">
'.($nbJourSeMoisPrecedent-$firstDay+$i).'</a></td>';
$jourTraiter++;
}


/* Le mois courrant */
for($i=1; $i<=$nbJourSeMois; $i++)
{
if ($jourTraiter == 0){echo '<tr>';}

	// Si c'est le jour d'aujourd'hui
	if (isSet($dateEvent[$i]))
	{
	$event = NULL;
		foreach($dateEvent[$i] AS $key => $value)
		{
			$event.= ($key+1).'. '. $value . ' ' . PHP_EOL;
		}
	echo '<td class="cal_event bubble"><a href="' . Router::url('calendar/event/year:'.$year.'/month:'.$month.'/day:'.$i) . '">' . $i . '</a><em><span></span>'.stripcslashes($event).'</em></td>';
	}
	elseif($jour == $i)
	{
	echo '<td class="cal_aujourdhui bubble"><a href="' . Router::url('calendar/event/year:'.$year.'/month:'.$month.'/day:'.$i) . '">' . $i . '</a></td>';
	}
	elseif($jour < $i)
	{	// Si pas				{
	echo '<td class="cal_jours_ap"><a href="' . Router::url('calendar/event/year:'.$year.'/month:'.$month.'/day:'.$i) . '">' . $i . '</a></td>';
	}
	elseif($jour > $i)
	{
	echo '<td class="cal_jours_av"><a href="' . Router::url('calendar/event/year:'.$year.'/month:'.$month.'/day:'.$i) . '">' . $i . '</a></td>';		
	}
$jourTraiter++;
if ($jourTraiter == 7){echo '<tr>';$jourTraiter=0;}
}


/* Les jours du mois suivant */
for($i=1; $jourTraiter!=0; $i++)
{
echo '<td class="cal_jours_av_ap"><a href="' . Router::url('calendar/event/year:'.$nextYear.'/month:'.$nextMonth.'/day:'.$i) . '">' . $i . '</a>
</td>';
$jourTraiter++;
if ($jourTraiter == 7){echo '<tr>';$jourTraiter=0;}
}


echo '</tbody></table>';
?>
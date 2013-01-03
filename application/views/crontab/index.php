	<div class="well">
	<p>Une t&acirc;che cron, est une t&acirc;che qui s'execute &agrave; intervale r&eacute;gulier. Il est donc possible d'executer une t&acirc;che le premier vendredi du mois, ou le lundi tout les trimestres et bien plus encore. Bref, cet un outils puissante et logique.<br>
	</p>
	</div>


<ul class="tabs" style="padding-top:10px;">
  <li class="active"><a href="#default">T&acirc;ches enregistr&eacute;</a></li>
  <li><a href="#cronadd">Enregistrer une nouvelle t&acirc;che</a></li>
</ul>

<div class="pill-content">
	<div class="active" id="default">
	<?php if (count($cronjob)):  ?>
	<table>
		<tr>
			<th>Minutes</th>
			<th>Heures</th>
			<th>Jours</th>
			<th>Mois</th>
			<th>Jour de la semaine</th>
			<th>Commande</th>
		</tr>
		<?php for($i=0; $i<count($cronjob); $i++):
		if (isSet($cronjob[$i]->comment)) 
		{
			echo '<tr><td colspan="7">' . $cronjob[$i]->comment . '<td></tr>';
		}
		?>
		<tr>
			<td><form method="post" action="<?php echo Router::url('crontab/delete'); ?>"><input type="hidden" name="dminute" value="<?php echo $cronjob[$i]->minute; ?>"><?php echo $cronjob[$i]->minute; ?></td>
			<td><input type="hidden" name="dhour" value="<?php echo $cronjob[$i]->hour; ?>"><?php echo $cronjob[$i]->hour; ?></td>
			<td><input type="hidden" name="ddayMonth" value="<?php echo $cronjob[$i]->dayMonth; ?>"><?php echo $cronjob[$i]->dayMonth; ?></td>
			<td><input type="hidden" name="dmonth" value="<?php echo $cronjob[$i]->month; ?>"><?php echo $cronjob[$i]->month; ?></td>
			<td><input type="hidden" name="ddayWeek" value="<?php echo $cronjob[$i]->dayWeek; ?>"><?php echo $cronjob[$i]->dayWeek; ?></td>
			<td><input type="hidden" name="dcommand" value="<?php echo $cronjob[$i]->commande; ?>"><?php echo $cronjob[$i]->commande; ?></td>
			<td><input type="submit" name="deleteTask" class="btn danger" value="Supprimer"></form></td>
		</tr>
		<?php endfor; ?>
	</table>
	<?php else:  ?>
		<p>Aucune t&acirc;che enregistr&eacute;</p>
	<?php endif; ?>
	
	</div>
	<div id="cronadd">




		<script language="JavaScript">
		function selectCheckbox(checkbox){checkbox.checked = (checkbox.checked) ? false : true;}
		function selectAllWeekDays(){myForm=document.getElementById('actionForm');var selectValueSet=(myForm.weekDay_all.value == 'true') ? false : true;myForm.weekDay_all.value=(selectValueSet) ? 'true' : 'false';myForm.weekDay_0.checked=selectValueSet;myForm.weekDay_1.checked=selectValueSet;myForm.weekDay_2.checked=selectValueSet;myForm.weekDay_3.checked=selectValueSet;myForm.weekDay_4.checked=selectValueSet;myForm.weekDay_5.checked=selectValueSet;myForm.weekDay_6.checked=selectValueSet;}
		function selectAllMonth(){myForm=document.getElementById('actionForm');var selectValueSet=(myForm.month_all.value == 'true') ? false : true;myForm.month_all.value=(selectValueSet) ? 'true' : 'false';myForm.month_1.checked=selectValueSet;myForm.month_2.checked=selectValueSet;myForm.month_3.checked=selectValueSet;myForm.month_4.checked=selectValueSet;myForm.month_5.checked=selectValueSet;myForm.month_6.checked=selectValueSet;myForm.month_7.checked=selectValueSet;myForm.month_8.checked=selectValueSet;myForm.month_9.checked=selectValueSet;myForm.month_10.checked=selectValueSet;myForm.month_11.checked=selectValueSet;myForm.month_12.checked=selectValueSet;}
		function selectAllDays(){myForm=document.getElementById('actionForm');var selectValueSet=(myForm.monthDay_all.value == 'true') ? false : true;myForm.monthDay_all.value=(selectValueSet) ? 'true' : 'false';myForm.monthDay_1.checked=selectValueSet;myForm.monthDay_2.checked=selectValueSet;myForm.monthDay_3.checked=selectValueSet;myForm.monthDay_4.checked=selectValueSet;myForm.monthDay_5.checked=selectValueSet;myForm.monthDay_6.checked=selectValueSet;myForm.monthDay_7.checked=selectValueSet;myForm.monthDay_8.checked=selectValueSet;myForm.monthDay_9.checked=selectValueSet;myForm.monthDay_10.checked=selectValueSet;myForm.monthDay_11.checked=selectValueSet;myForm.monthDay_12.checked=selectValueSet;myForm.monthDay_13.checked=selectValueSet;myForm.monthDay_14.checked=selectValueSet;myForm.monthDay_15.checked=selectValueSet;myForm.monthDay_16.checked=selectValueSet;myForm.monthDay_17.checked=selectValueSet;myForm.monthDay_18.checked=selectValueSet;myForm.monthDay_19.checked=selectValueSet;myForm.monthDay_20.checked=selectValueSet;myForm.monthDay_21.checked=selectValueSet;myForm.monthDay_22.checked=selectValueSet;myForm.monthDay_23.checked=selectValueSet;myForm.monthDay_24.checked=selectValueSet;myForm.monthDay_25.checked=selectValueSet;myForm.monthDay_26.checked=selectValueSet;myForm.monthDay_27.checked=selectValueSet;myForm.monthDay_28.checked=selectValueSet;myForm.monthDay_29.checked=selectValueSet;myForm.monthDay_30.checked=selectValueSet;myForm.monthDay_31.checked=selectValueSet;}
		function selectAllHours(){myForm=document.getElementById('actionForm');var selectValueSet=(myForm.dayHour_all.value == 'true') ? false : true;myForm.dayHour_all.value=(selectValueSet) ? 'true' : 'false';myForm.dayHour_0.checked=selectValueSet;myForm.dayHour_1.checked=selectValueSet;myForm.dayHour_2.checked=selectValueSet;myForm.dayHour_3.checked=selectValueSet;myForm.dayHour_4.checked=selectValueSet;myForm.dayHour_5.checked=selectValueSet;myForm.dayHour_6.checked=selectValueSet;myForm.dayHour_7.checked=selectValueSet;myForm.dayHour_8.checked=selectValueSet;myForm.dayHour_9.checked=selectValueSet;myForm.dayHour_10.checked=selectValueSet;myForm.dayHour_11.checked=selectValueSet;myForm.dayHour_12.checked=selectValueSet;myForm.dayHour_13.checked=selectValueSet;myForm.dayHour_14.checked=selectValueSet;myForm.dayHour_15.checked=selectValueSet;myForm.dayHour_16.checked=selectValueSet;myForm.dayHour_17.checked=selectValueSet;myForm.dayHour_18.checked=selectValueSet;myForm.dayHour_19.checked=selectValueSet;myForm.dayHour_20.checked=selectValueSet;myForm.dayHour_21.checked=selectValueSet;myForm.dayHour_22.checked=selectValueSet;myForm.dayHour_23.checked=selectValueSet;}
		function selectAllMinutes(){myForm=document.getElementById('actionForm');var selectValueSet=(myForm.minute_all.value == 'true') ? false : true;myForm.minute_all.value=(selectValueSet) ? 'true' : 'false';myForm.minute_0.checked=selectValueSet;myForm.minute_5.checked=selectValueSet;myForm.minute_10.checked=selectValueSet;myForm.minute_15.checked=selectValueSet;myForm.minute_20.checked=selectValueSet;myForm.minute_25.checked=selectValueSet;myForm.minute_30.checked=selectValueSet;myForm.minute_35.checked=selectValueSet;myForm.minute_40.checked=selectValueSet;myForm.minute_45.checked=selectValueSet;myForm.minute_50.checked=selectValueSet;myForm.minute_55.checked=selectValueSet;}
		function selectMonthTrimstre(){myForm= document.getElementById('actionForm');myForm.month_1.checked=false;myForm.month_2.checked=false;myForm.month_3.checked=true;myForm.month_4.checked=false;myForm.month_5.checked=false;myForm.month_6.checked=true;myForm.month_7.checked=false;myForm.month_8.checked=false;myForm.month_9.checked=true;myForm.month_10.checked=false;myForm.month_11.checked=false;myForm.month_12.checked=true;}
		function selectMonthSemstre(){myForm=document.getElementById('actionForm');myForm.month_1.checked=false;myForm.month_2.checked=false;myForm.month_3.checked=false;myForm.month_4.checked=false;myForm.month_5.checked=false;myForm.month_6.checked=true;myForm.month_7.checked=false;myForm.month_8.checked=false;myForm.month_9.checked=false;myForm.month_10.checked=false;myForm.month_11.checked=false;myForm.month_12.checked=true;}
		function selectEveryTwoHour(){myForm=document.getElementById('actionForm');myForm.dayHour_0.checked=true;myForm.dayHour_1.checked=false;myForm.dayHour_2.checked=true;myForm.dayHour_3.checked=false;myForm.dayHour_4.checked=true;myForm.dayHour_5.checked=false;myForm.dayHour_6.checked=true;myForm.dayHour_7.checked=false;myForm.dayHour_8.checked=true;myForm.dayHour_9.checked=false;myForm.dayHour_10.checked=true;myForm.dayHour_11.checked=false;myForm.dayHour_12.checked=true;myForm.dayHour_13.checked=false;myForm.dayHour_14.checked=true;myForm.dayHour_15.checked=false;myForm.dayHour_16.checked=true;myForm.dayHour_17.checked=false;myForm.dayHour_18.checked=true;myForm.dayHour_19.checked=false;myForm.dayHour_20.checked=true;myForm.dayHour_21.checked=false;myForm.dayHour_22.checked=true;myForm.dayHour_23.checked=false;}
		function selectEveryTreeHour(){myForm=document.getElementById('actionForm');myForm.dayHour_0.checked=true;myForm.dayHour_1.checked=false;myForm.dayHour_2.checked=false;myForm.dayHour_3.checked=true;myForm.dayHour_4.checked=false;myForm.dayHour_5.checked=false;myForm.dayHour_6.checked=true;myForm.dayHour_7.checked=false;myForm.dayHour_8.checked=false;myForm.dayHour_9.checked=true;myForm.dayHour_10.checked=false;myForm.dayHour_11.checked=false;myForm.dayHour_12.checked=true;myForm.dayHour_13.checked=false;myForm.dayHour_14.checked=false;myForm.dayHour_15.checked=true;myForm.dayHour_16.checked=false;myForm.dayHour_17.checked=false;myForm.dayHour_18.checked=true;myForm.dayHour_19.checked=false;myForm.dayHour_20.checked=false;myForm.dayHour_21.checked=true;myForm.dayHour_22.checked=false;myForm.dayHour_23.checked=false;}
		function selectEveryForeHour(){myForm= document.getElementById('actionForm');myForm.dayHour_0.checked=true;myForm.dayHour_1.checked=false;myForm.dayHour_2.checked=false;myForm.dayHour_3.checked=false;myForm.dayHour_4.checked=true;myForm.dayHour_5.checked=false;myForm.dayHour_6.checked=false;myForm.dayHour_7.checked=false;myForm.dayHour_8.checked=true;myForm.dayHour_9.checked=false;myForm.dayHour_10.checked=false;myForm.dayHour_11.checked=false;myForm.dayHour_12.checked=true;myForm.dayHour_13.checked=false;myForm.dayHour_14.checked=false;myForm.dayHour_15.checked=false;myForm.dayHour_16.checked=true;myForm.dayHour_17.checked=false;myForm.dayHour_18.checked=true;myForm.dayHour_19.checked=false;myForm.dayHour_20.checked=true;myForm.dayHour_21.checked=false;myForm.dayHour_22.checked=true;myForm.dayHour_23.checked=false;}
		function selectEverySixHour(){myForm= document.getElementById('actionForm');myForm.dayHour_0.checked=true;myForm.dayHour_1.checked=false;myForm.dayHour_2.checked=false;myForm.dayHour_3.checked=false;myForm.dayHour_4.checked=false;myForm.dayHour_5.checked=false;myForm.dayHour_6.checked=true;myForm.dayHour_7.checked=false;myForm.dayHour_8.checked=false;myForm.dayHour_9.checked=false;myForm.dayHour_10.checked=false;myForm.dayHour_11.checked=false;myForm.dayHour_12.checked=true;myForm.dayHour_13.checked=false;myForm.dayHour_14.checked=false;myForm.dayHour_15.checked=false;myForm.dayHour_16.checked=false;myForm.dayHour_17.checked=false;myForm.dayHour_18.checked=true;myForm.dayHour_19.checked=false;myForm.dayHour_20.checked=false;myForm.dayHour_21.checked=false;myForm.dayHour_22.checked=false;myForm.dayHour_23.checked=false;}
		function selectEveryHuitHour(){myForm= document.getElementById('actionForm');myForm.dayHour_0.checked=true;myForm.dayHour_1.checked=false;myForm.dayHour_2.checked=false;myForm.dayHour_3.checked=false;myForm.dayHour_4.checked=false;myForm.dayHour_5.checked=false;myForm.dayHour_6.checked=false;myForm.dayHour_7.checked=false;myForm.dayHour_8.checked=true;myForm.dayHour_9.checked=false;myForm.dayHour_10.checked=false;myForm.dayHour_11.checked=false;myForm.dayHour_12.checked=false;myForm.dayHour_13.checked=false;myForm.dayHour_14.checked=false;myForm.dayHour_15.checked=false;myForm.dayHour_16.checked=true;myForm.dayHour_17.checked=false;myForm.dayHour_18.checked=false;myForm.dayHour_19.checked=false;myForm.dayHour_20.checked=false;myForm.dayHour_21.checked=false;myForm.dayHour_22.checked=false;myForm.dayHour_23.checked=false;}
		function selectEveryDixMinute(){myForm=document.getElementById('actionForm');myForm.minute_0.checked=true;myForm.minute_5.checked=false;myForm.minute_10.checked=true;myForm.minute_15.checked=false;myForm.minute_20.checked=true;myForm.minute_25.checked=false;myForm.minute_30.checked=true;myForm.minute_35.checked=false;myForm.minute_40.checked=true;myForm.minute_45.checked=false;myForm.minute_50.checked=true;myForm.minute_55.checked=false;}
		function selectEveryVinghtMinute(){myForm=document.getElementById('actionForm');myForm.minute_0.checked=true;myForm.minute_5.checked=false;myForm.minute_10.checked=false;myForm.minute_15.checked=false;myForm.minute_20.checked=true;myForm.minute_25.checked=false;myForm.minute_30.checked=false;myForm.minute_35.checked=false;myForm.minute_40.checked=true;myForm.minute_45.checked=false;myForm.minute_50.checked=false;myForm.minute_55.checked=false;}
		function selectEveryTrenteMinute(){myForm=document.getElementById('actionForm');myForm.minute_0.checked=true;myForm.minute_5.checked=false;myForm.minute_10.checked=false;myForm.minute_15.checked=false;myForm.minute_20.checked=false;myForm.minute_25.checked=false;myForm.minute_30.checked=true;myForm.minute_35.checked=false;myForm.minute_40.checked=false;myForm.minute_45.checked=false;myForm.minute_50.checked=false;myForm.minute_55.checked=false;}
			</script>
				
		<form method="post" id="actionForm">
		<input type="hidden" name="month_all" value="false"><input type="hidden" name="weekDay_all" value="false"><input type="hidden" name="monthDay_all" value="false"><input type="hidden" name="dayHour_all" value="false"><input type="hidden" name="minute_all" value="false">
		<table class="actionTable" align="center" border="0" cellpadding="5" cellspacing="0">
		<tbody>


		<tr style="background-color: #ededed;"><td style="font-weight:bold;" colspan="8">P&eacute;riodicit&eacute; - Mois</td></tr>
		<tr>
			<td colspan="8">
				<strong>Aide: </strong>Quel mois la t&acirc;che doit-elle &ecirc;tre execut&eacute; ? Si aucun mois n'est d&eacute;finie, n'importe quel mois.<br>
			</td>
		</tr>

		<tr>
			<td> </td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectMonthTrimstre(); return false;">Tous les trimestre</button></td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectMonthSemstre(); return false;">Tous les semestre</button></td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectAllMonth(); return false;">Tous les mois</button></td>
			<td colspan="2"> </td>
		</tr>

		<tr style="background-color : #ffffff;">
			<td valign="top"></td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_1" > janvier</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_2"> f&eacute;vrier</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_3"> mars</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_4"> avril</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_5"> mais</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_6" colspan="2"> juin</td>
			
		</tr>
		<tr style="background-color : #ffffff;">
			<td valign="top"></td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_7" > juillet</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_8"> ao&ucirc;t</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_9"> septembre</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_10"> octobre</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_11"> novembre</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="month_12" colspan="2"> d&eacute;cembre</td>
		</tr>

		<tr>
		<td colspan="8"><br></td>
		</tr>

		<tr style="background-color: #ededed;"><td style="font-weight:bold;" colspan="8">P&eacute;riodicit&eacute; - Jours</td></tr>
		<tr>
			<td colspan="8">
				<strong>Aide: </strong>Quel jour de la semaine la t&acirc;che doit-elle &ecirc;tre execut&eacute; ? Si aucun jour n'est d&eacute;finie, n'importe quel jour.<br>
				<strong>Astuce: </strong>Pour executer la t&acirc;che le premier vendredi du mois, selectionn&eacute; le vendredi et les 7 premiers jours dans la zone jours du mois.
			</td>
		</tr>

		<tr>
			<td colspan="3"><div  title="Jour(s) fixe(s)">Tous les</div></td>
			<td colspan="2"></td>
			<td colspan="3"><button type="button" class="btn" style="cursor:pointer;" onclick="selectAllWeekDays(); return false;">Tous les jours</button></td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_1" > lundi</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_2"> mardi</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_3"> mercredi</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_4"> jeudi</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_5"> vendredi</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_6"> samedi</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);" colspan="2"><input type="checkbox"  onclick="selectCheckbox(this);" name="weekDay_0"> dimanche</td>
		</tr>


		<tr>
		<td colspan="8"><br></td>
		</tr>

		<tr>
			<td colspan="8">
				<strong>Aide: </strong>Quel jour du mois la t&acirc;che doit-elle &ecirc;tre execut&eacute; ? Si aucun jour n'est d&eacute;finie, n'importe quel jour.<br>
				<strong>Astuce: </strong>Pour executer la t&acirc;che un mois sur deux, indiqu&eacute; uniquement 31
			</td>
		</tr>
		<tr>
			<td colspan="3"><div  title="Jour(s) fixe(s)">Jour(s) fixe(s)</div></td>
			<td colspan="2"></td>
			<td colspan="3"><button type="button" class="btn" style="cursor:pointer;" onclick="selectAllDays(); return false;">Tous les jours</button></td>
		</tr>

		<tr style="background-color : #ffffff;">
			<td rowspan="5" valign="top"></td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_1" > 1</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_2"> 2</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_3"> 3</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_4"> 4</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_5"> 5</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_6"> 6</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_7"> 7</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_8"> 8</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_9"> 9</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_10"> 10</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_11"> 11</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_12"> 12</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_13"> 13</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_14"> 14</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_15">  15</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_16"> 16</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_17"> 17</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_18"> 18</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_19"> 19</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_20"> 20</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_21"> 21</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_22"> 22</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_23"> 23</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_24"> 24</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_25"> 25</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_26"> 26</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_27"> 27</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_28"> 28</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_29"> 29</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_30"> 30</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="monthDay_31"> 31</td>
		</tr>

		<tr>
		<td colspan="8"><br></td>
		</tr>

		<tr style="background-color: #ededed;"><td style="font-weight:bold;" colspan="8">P&eacute;riodicit&eacute; - Heures</td></tr>
		<tr>
			<td colspan="8">
				<strong>Aide: </strong>A quel heure, souhaitez-vous que la t&acirc;che s'execute ? Si aucune heure n'est d&eacute;finie, alors minuit.<br>
				<strong>Astuce: </strong>Pour des t&acirc;ches longues, tel que les back-ups, pr&eacute;f&eacute;rez une zone horaire creuse.
			</td>
		</tr>

		<tr>
			<td></td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectEveryTwoHour(); return false;">Tous les deux heures</button></td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectEveryTreeHour(); return false;">Tous les trois heures</button></td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectEveryForeHour(); return false;">Tous les quatre heures</button></td>
			<td> </td>
		</tr>

		<tr>
			<td> </td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectEverySixHour(); return false;">Tous les six heures</button></td>
			<td colspan="2"><button type="button" class="btn" style="cursor:pointer;" onclick="selectEveryHuitHour(); return false;">Tous les huit heures</button></td>
			<td colspan="3"><button type="button" class="btn"  style="cursor:pointer;" onclick="selectAllHours();">Toutes les heures</button></td>
		</tr>


		<tr style="background-color : #ffffff;">
			<td rowspan="4" valign="top"></td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_0"> 0h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_1"> 1h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_2"> 2h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_3"> 3h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_4"> 4h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_5"> 5h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_6"> 6h</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_7"> 7h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_8"> 8h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_9"> 9h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_10"> 10h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_11"> 11h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_12"> 12h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_13"> 13h</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_14"> 14h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_15"> 15h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_16"> 16h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_17"> 17h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_18"> 18h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_19"> 19h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_20"> 20h</td>
		</tr>
		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_21"> 21h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_22"> 22h</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="dayHour_23"> 23h</td>
		</tr>

		<tr>
		<td colspan="8"><br></td>
		</tr>


		<tr style="background-color: #ededed;"><td style="font-weight:bold;" colspan="8">P&eacute;riodicit&eacute; - Minutes</td></tr>




		<tr>
			<td></td>
			<td colspan="3">
				<button type="button" class="btn"  style="cursor:pointer;" onclick="selectAllMinutes();">Toutes les cinq minutes</button>
			</td>
			<td colspan="3">
			
			<button type="button" class="btn" style="cursor:pointer;" onclick="selectEveryDixMinute(); return false;">Tous les dix minutes</button>&nbsp;
			</td>

		</tr>

		<tr>
			<td></td>
			<td colspan="3">
			<button type="button" class="btn" style="cursor:pointer;" onclick="selectEveryVinghtMinute(); return false;">Tous les vingts minutes</button>&nbsp;
			</td>
			<td colspan="3">
			<button type="button" class="btn"  style="cursor:pointer;" onclick="selectEveryTrenteMinute(); return false;">Toutes les trente minutes</button>&nbsp;

			</td>
		</tr>


		<tr style="background-color : #ffffff;">
			<td rowspan="2" valign="top"></td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_0"> 0</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_5"> 5</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_10"> 10</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_15"> 15</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_20"> 20</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_25" colspan="2"> 25</td>

		</tr>

		<tr style="background-color : #ffffff;">
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_30"> 30</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_35"> 35</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_40"> 40</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_45"> 45</td>
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_50"> 50</td>
			
			<td onclick="selectCheckbox(this.getElementsByTagName('input')[0]);"><input type="checkbox"  onclick="selectCheckbox(this);" name="minute_55" colspan="2"> 55</td>
		</tr>

		<tr>
		<td colspan="8"><br></td>
		</tr>

		<tr style="background-color: #ededed;"><td style="font-weight:bold;" colspan="8">Requ&ecirc;te</td></tr>
		<tr>
			<td colspan="8">
				<strong>Aide: </strong>La requ&ecirc;te est une page web g&eacute;n&eacute;ralement typ&eacute; "http://site.tld/cron" qui execute un script pr&eacute;cis, tel que la mise &agrave; jour d'un cache ou le traitement de donn&eacute;es redondantes.<br>
				<strong>Astuce: </strong>Pour le chargement d'une page web, utilis&eacute; "wget -o /dev/null http://VOTRE.SITE/PAGE" pour le traitement comme un script PHP "php5 PATH/VERS/LE/FICHIER"
			</td>
		</tr>

		<tr>
			<td></td>
			<td colspan="6">
				<input type="text" name="command" style="width:100%" value="wget -o /dev/null ">
			</td>
			<td></td>
		</tr>

		<tr>
			<td colspan="2">Description de la t&acirc;che</td>
			<td colspan="6">
				<input type="text" name="description" style="width:100%">
			</td>
		</tr>


		</tbody>
		</table>
		<?php echo Form::getInstance()->input('submit', 'Enregister', array('type' => 'submit', 'class' => 'btn')); ?>
		</form>

	</div>
</div>
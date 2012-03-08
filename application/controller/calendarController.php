<?php
/**
* @title Calendar
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Calendrier evenementielle, avec ajout par le propriètaire
*
* change list
	* 2012-02-20 L'ajout d'evenement est possible
	* 2012-02-19 Affichage mensuelle et journalié
*/


class calendarController extends Controller {
private function load()
{
	if ($this->mvc->Acl->isAllowed())
	{
	$this->mvc->Page->setMenu(
			'Calendrier',
			'Ajouter',
			Router::url('calendar/addevent')
		);
	}
}


public function index()
{
$this->load();


	loadFunction('bbcode');
	$this->mvc->Page->setBreadcrumb('calendar','Calendrier');
	
	//Annee en 4 chiffres
	$year = isSet($this->mvc->Request->params['year']) ? $this->mvc->Request->params['year'] : date('Y');
	$this->mvc->Template->year = $year;
	
	//Mois sans les zeros initiaux	1 a 12
	$month = isSet($this->mvc->Request->params['month']) ? $this->mvc->Request->params['month'] : date('n');
	$this->mvc->Template->month = $month;
	
	//Jour sans les zeros initiaux	1 a 12 
	$day = isSet($this->mvc->Request->params['day']) ? $this->mvc->Request->params['day'] : 0;//date('j');
	$this->mvc->Template->day = $day;
	
	// Les mois français
	$moisFr = array('de Janvier', 'de F&eacute;vrier', 'de Mars', 'd\'Avril', 'de Mai', 'de Juin', 'de Juillet', 'd\'Ao&ucirc;t', 'de Septembre', 'd\'Octobre', 'de Novembre', 'de D&eacute;cembre');
	
	
	
	/**
	* Verifie que la date est correcte
	*/
	if (checkdate($month, 1, $year))
	{
	$this->mvc->Page->setPageTitle('Calendrier ' . strtolower($moisFr[$month-1]) . ' ' . $year);
	
	// Acces a la base de donnée
	$calendar = $this->loadModel('Calendar');
	$req = array(
		'fields'		=> 'day, note, label, labelword',
		'conditions'	=> array('year' => $year, 'month' => $month, 'id_member' => 0),
		);
	if ($this->mvc->Session->isLogged())
	{
	$req['conditions']	= 
		' year = '.$year.
		' AND month = '.$month.
		' AND (id_member = ' . $this->mvc->Session->user('id'). ' OR id_member = 0)';
	}
	
	$query = $calendar->find($req, PDO::FETCH_ASSOC);

	$dateEvent = array();
		foreach($query AS $key => $value)
		{
		$label = ($value['label'] != 'default') ? $value['labelword']. ' ' : NULL;
			$dateEvent[$value['day']][] = $label . stripBBcode($value['note']);
		}

	$this->mvc->Template->dateEvent = $dateEvent;
	$this->mvc->Template->show('calendar/index');
	}
	else	// Date incorrect
	{
		$this->mvc->Session->setFlash('Date incorrect', 'error');
	}
}



public function event()
{
$this->load();
	loadFunction('bbcode');
	
	// Année complete
	$year = isSet($this->mvc->Request->params['year']) ? $this->mvc->Request->params['year'] : date('Y');
	$this->mvc->Template->year = $year;
	
	//Mois sans les zeros initiaux	1 a 12
	$month = isSet($this->mvc->Request->params['month']) ? $this->mvc->Request->params['month'] : date('n');
	$this->mvc->Template->month = $month;

	//Jour sans les zeros initiaux	1 a 12 
	$day = isSet($this->mvc->Request->params['day']) ? $this->mvc->Request->params['day'] : date('j');
	$this->mvc->Template->day = $day;	

	
	
	/**
	* Verifie que la date est correcte
	*/
	if (checkdate($month, $day, $year))
	{
	$moisFr = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');

	$this->mvc->Page->setPageTitle('Evenements du '.$day.' '.$moisFr[$month-1] . ' ' . $year);
	$this->mvc->Page->setBreadcrumb('calendar','Calendrier');
	$this->mvc->Page->setBreadcrumb('calendar/year:'.$year.'/month:'.$month, $moisFr[$month-1] . ' ' .  $year);

	$this->mvc->Template->monthFr = $moisFr[$month-1];

		// Acces a la base de donnée
		$calendar = $this->loadModel('Calendar');
		$req = array(
			'fields'		=> 'note, heure, minute, label, labelword',
			'conditions'	=> array('year' => $year, 'month' => $month, 'day' => $day, 'id_member' => 0),
			'order'			=> 'Calendar.heure ASC ',
			);
			
	if ($this->mvc->Session->isLogged())
	{
	$req['conditions']	= 
		' year = '.$year .
		' AND month = '.$month .
		' AND day = ' . $day .
		' AND (id_member = ' . $this->mvc->Session->user('id'). ' OR id_member = 0)';
	}
			
		$query = $calendar->find($req, PDO::FETCH_ASSOC);
		// END Acces a la base de donnée
		
		
		// Evenement de la journée
		$dateEvent = array();
		foreach($query AS $key => $value)
		{
			$label=NULL;
			if ($value['label'] != "default")
			{
			$label = '<span class="label '.$value['label'].'">'.$value['labelword'].'</span>&nbsp;';
			}
		
			if (isSet($dateEvent[$value['heure'].':'.$value['minute']]))
			{
			$dateEvent[$value['heure'].':'.$value['minute']] .= '<br>'.$label.bbcode($value['note']);
			}
			else
			{
			$dateEvent[$value['heure'].':'.$value['minute']] = $label.bbcode($value['note']);
			}
		}
		$this->mvc->Template->dateEvent = $dateEvent;
		// END Evenement de la journée


	$this->mvc->Template->show('calendar/eventOfTheDay');

	}
	else	// Date incorrect
	{
		$this->mvc->Session->setFlash('Date incorrect', 'error');
	}
}


public function addevent()
{
$this->load();

	if ($this->mvc->Acl->isAllowed())
	{
	$this->mvc->Page->setBreadcrumb('calendar','Calendrier');
	$this->mvc->Page->setPAgeTitle('Ajout d\'un évenement');
	$calendar = $this->loadModel('Calendar');
		if (!empty($this->mvc->Request->data))
		{	
			if ($calendar->validates($this->mvc->Request->data))
			{
			
			
			//$this->mvc->Session->user('id'));
				if ($calendar->addEvent($this->mvc->Request->data))
				{
				$this->mvc->Session->setFlash('&Eacute;venement ajout&eacute;');
				Router::redirect('calendar/addevent');
				}
			}
			else
			{
			$this->mvc->Form->setErrors($calendar->errors);
			}
		}
	$this->mvc->Template->show('calendar/addevent');
	}
	else
	{
	$this->mvc->Session->setFlash('Date incorrect', 'error');
	}

}
}
?>
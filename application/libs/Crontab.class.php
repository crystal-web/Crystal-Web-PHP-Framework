<?php
Class Crontab {
/*
#  exÃ©cution tous les quarts d'heure de 15h Ã  19h du lundi au vendredi
#  seulement en 1Ã¨re quinzaine du troisiÃ¨me trimestre
minute  hour  dayMonth  month  dayWeek commande
*/
private $uid;
private $debug = false;
private $meCrontab = array();
private $otherCrontab = array();


const debut = '# PHP-Crontab start-event uid:';
const fin = '# PHP-Crontab stop-event uid:';
const comment = '# PHP-Crontab COMMENT:';

    public function __construct($uid, $debug = false)
    {
        $this->uid = md5($uid);
		$this->debug = $debug;
		$this->reload();
	}
	
	private function reload()
	{
		if (!$this->debug)
		{
			Log::setLog('Recuperation des taches crontab', 'PHP-CronTab');
			exec('crontab -l', $oldCrontab);        /* on récupère l'ancienne crontab dans $oldCrontab */
		} else {
			Log::setLog('Recuperation du fichier crontab', 'PHP-CronTab');
			
			if (!file_exists('crontab.lst'))
			{
				Log::setLog('Fichier inexistant, chargement de crontab -l', 'PHP-CronTab');
				exec('crontab -l', $oldCrontab);	/* on récupère l'ancienne crontab dans $oldCrontab */
			}
			else
			{
				Log::setLog('Recuperation du fichier', 'PHP-CronTab');
				$oldCrontab = file_get_contents(__APP_PATH . DS . 'cache' . DS .'crontab.lst');
				$oldCrontab = explode(PHP_EOL, $oldCrontab);
			}
		}
		
		
		
		if (count($oldCrontab))
		{
			/*
			 * On parcours les taches,
			 * On ne traite QUE les taches enregistré par la class
			 */
			$this->meCrontab = array();
			$isSection = false;
			foreach($oldCrontab as $index => $ligne)    /* copie $oldCrontab dans $newCrontab et ajoute le nouveau script */
			{
				if ($ligne == self::debut . $this->uid) { $isSection = true; }
			
				if ($isSection)         /* on est dans la section gÃ©rÃ©e automatiquement */
				{
					$this->meCrontab[] = $ligne;
				}
				else
				{
					$this->otherCrontab[] = $ligne;
				}
				
				if ($ligne == self::fin . $this->uid) { $isSection = false; }
			}
		}
	}
    
	/**
	 *	Ajoute une tache
	 * @return this
	 */
	 
	 //$minute, $hour, $dayMonth, $month, $dayWeek, $commande
    public function add($minute, $hour, $dayMonth, $month, $dayWeek, $commande, $comment = NULL)
    {
		
		
		$this->meCrontab[] = self::debut . $this->uid;
		if (strlen($comment)) { $this->meCrontab[] = self::comment . ' ' . $comment; }
		$this->meCrontab[] = $minute . ' ' . $hour . ' ' . $dayMonth . ' ' . $month . ' ' . $dayWeek . ' ' . $commande;
		$this->meCrontab[] = self::fin . $this->uid;
		
		return $this;
	}
    
	
	/**
	 *	Supprime une tache
	 * @return this
	 */
    public function remove($minute, $hour, $dayMonth, $month, $dayWeek, $commande)
    {
	$searchIt = $minute . ' ' . $hour . ' ' . $dayMonth . ' ' . $month . ' ' . $dayWeek . ' ' . $commande;
	Log::setLog('Recherche "' . $searchIt . '" dans les taches', 'PHP-CronTab');
	
	$isSection = false;
        for ($i=0; $i<count($this->meCrontab);$i++)
		{

			if ($searchIt == $this->meCrontab[$i])
			{
				Log::setLog('A trouvé "' . $searchIt . '" dans les taches', 'PHP-CronTab');
				unset($this->meCrontab[$i]);
				
				Log::setLog('Suppression du balisage', 'PHP-CronTab');
				unset($this->meCrontab[($i+1)]);
					
				$searchSection = explode(' ', $this->meCrontab[($i-1)]);
				if ($searchSection[1] == 'PHP-Crontab' && $searchSection[2] == 'COMMENT:')
				{
					Log::setLog('Ligne precedente est un commentaire, suppression', 'PHP-CronTab');
					unset($this->meCrontab[($i-1)]);
					unset($this->meCrontab[($i-2)]);
				} else {
					unset($this->meCrontab[($i-1)]);
					Log::setLog('Ligne precedent n\'est pas un comment', 'PHP-CronTab');
				}
				return $this;
				
			}

		}
		
		
		return $this;
    }
	
	/**
	 *	Retourne les taches cron de UID
	 * @return array
	 */
	public function getTask()
	{
		return $this->meCrontab;
	}
	
	/**
	 *	Retourne toutes les taches cron
	 * @return array
	 */
	public function getAllTask()
	{
		return array_merge($this->meCrontab, $this->otherCrontab);
	}
	
	public function getTaskHuman()
	{
		$meTask = $this->getTask();
		$humanReadable = array();
		for($i=0; $i<count($meTask); $i++)
		{
			if ($meTask[$i][0] != '#')
			{
				$data = new stdClass();
				$exploded = explode(' ', $meTask[$i]);
				
				$data->minute	= $exploded[0];		unset($exploded[0]);
				$data->hour		= $exploded[1];		unset($exploded[1]);
				$data->dayMonth	= $exploded[2];		unset($exploded[2]);
				$data->month	= $exploded[3];		unset($exploded[3]);
				$data->dayWeek	= $exploded[4];		unset($exploded[4]);
				$data->commande	= implode(' ', $exploded);
				
				$search = explode(' ', $meTask[($i-1)]);
				if ($search[1] == 'PHP-Crontab' && $search[2] == 'COMMENT:')
				{
					unset($search[0],$search[1], $search[2]);
					$data->comment = implode(' ', $search);
				}
				
				
				
				$humanReadable[] = $data;
			}
		}
		
		return $humanReadable;
	}
	
	/**
	 *	Enregistre les changements
	 */
	public function saveChange()
	{
		file_put_contents(__APP_PATH . DS . 'cache' . DS .'crontab.lst', implode( PHP_EOL, array_merge( $this->meCrontab, $this->otherCrontab ) ) );
		
		if (!$this->debug)
		{
			exec('crontab ' . __APP_PATH . DS . 'cache' . DS .'crontab.lst');
		}
		$this->reload();
	}


}
?>
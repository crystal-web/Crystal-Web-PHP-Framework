<?php
Class crontabController extends Controller {
    
	

	
	public function index()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		$config = Config::getInstance();
		
		if (!$acl->isAllowed())
		{
			return $this->loadController('error')->e404();
		}
		
		$page->setPageTitle('T&acirc;ches Cron');
		
		$o = new Crontab(__CW_PATH);
		if ($request->data)
		{
			
			if (isSet($request->data->command))
			{
				$command = (strlen($request->data->command)) ? $request->data->command : 'wget -o /dev/null ' . $config->getSiteUrl();
				$command = clean(trim($command), 'str');
			} else { $command = 'wget -o /dev/null ' . $config->getSiteUrl(); }
			
			if (isSet($request->data->description))
			{
				$description = (strlen($request->data->description)) ? $request->data->description : NULL;
			} else { $description = NULL; }
			
			
			if ($command != 'wget -o /dev/null' && strlen($command) > 2)
			{
				$o->add($this->postMinute(), $this->postDayHour(),$this->postDayMonth(), $this->postMonth(), $this->postWeekDay(), $command, $description);
				$o->saveChange();
			}
		}

		$template->cronjob = $o->getTaskHuman();
		$template->show('crontab/index');
	}
	
	public function delete()
	{
		$acl = AccessControlList::getInstance();
		$request = Request::getInstance();
		
		if (!$acl->isAllowed())
		{
			return $this->loadController('error')->e404();
		}
		
		if (
			isSet($request->data->dcommand) &&
			isSet($request->data->ddayWeek) &&
			isSet($request->data->dmonth) &&
			isSet($request->data->ddayMonth) &&
			isSet($request->data->dhour) &&
			isSet($request->data->dminute)
			)
		{
			$o = new Crontab(__CW_PATH);
			$o->remove(
				clean($request->data->dminute, 'str'),
				clean($request->data->dhour, 'str'),
				clean($request->data->ddayMonth, 'str'),
				clean($request->data->dmonth, 'str'),
				clean($request->data->ddayWeek, 'str'),
				clean($request->data->dcommand, 'str')
				);
			$o->saveChange();
		}
		Router::redirect('crontab');

	}
	
	
	
	
	
	
	
	/****************************POSTED VALUE FOR CRON****************************/
	
	
	private function postMinute()
	{
		$request = Request::getInstance();
		$minute = NULL;
		
		for($i=0; $i<60; $i++)
		{
		$nameI = 'minute_' . $i;
			if (isSet($request->data->$nameI))
			{
				$minute .= $i . ',';
			}
			$i += 4;
		}

		$minute = trim($minute, ',');

		
		switch(true)
		{
			case (strlen($minute) >= 33):
				return '*/5';
			break;
			case ($minute == '0,10,20,30,40,50'):
				return '*/10';
			break;
			case ($minute == '0,15,30,45'):
				return '*/15';
			break;
			case ($minute == '0,20,40'):
				return '*/20';
			break;
			case ($minute == '0,30'):
				return '*/30';
			break;
			case (strlen($minute) == 0):
				return '*';
			break;
			default:
				return $minute;
			break;
		}
	}
	
	private function postDayHour()
	{
		$request = Request::getInstance();
		$dayHour = NULL;
		
		for($i=0; $i<24; $i++)
		{
		$nameI = 'dayHour_' . $i;
			if (isSet($request->data->$nameI))
			{
				$dayHour .= $i . ',';
			}
		}

		$dayHour = trim($dayHour, ',');
		
		
		switch(true)
		{
			case (strlen($dayHour) >= 61):
				return '*/1';
			break;
			case ($dayHour == '0,2,4,6,8,10,12,14,16,18,20,22'):
				return '*/2';
			break;
			case ($dayHour == '0,3,6,9,12,15,18,21'):
				return '*/3';
			break;
			case ($dayHour == '0,4,8,12,16,20'):
				return '*/4';
			break;
			case ($dayHour == '0,6,12,18'):
				return '*/6';
			break;
			case ($dayHour == '0,8,16'):
				return '*/8';
			break;
			case (strlen($dayHour) == 0):
				return '*';
			break;
			default:
				return $dayHour;
			break;
		}
	}
	
	private function postDayMonth()
	{

		$request = Request::getInstance();
		$dayMonth = NULL;
		
		for($i=1; $i<32; $i++)
		{
		$nameI = 'monthDay_' . $i;
			if (isSet($request->data->$nameI))
			{
				$dayMonth .= $i . ',';
			}
		}
		$dayMonth = trim($dayMonth, ',');
		

		switch(true)
		{
			case (strlen($dayMonth) >= 83):
				return '*/1';
			break;
			case (strlen($dayMonth) == 0):
				return '*';
			break;
			default:
				return $dayMonth;
			break;
		}
		
		
	}
	
	private function postMonth()
	{

		$request = Request::getInstance();
		$month = NULL;
		
		for($i=1; $i<13; $i++)
		{
		$nameI = 'month_' . $i;
			if (isSet($request->data->$nameI))
			{
				$month .= $i . ',';
			}
		}

		$month = trim($month, ',');

		switch(true)
		{
			case (strlen($month) >= 26):
				return '*/1';
			break;
			case ($month == '3,6,9,12'):
				return '*/3';
			break;
			case ($month == '6,12'):
				return '*/6';
			break;
			case (strlen($month) == 0):
				return '*';
			break;
			default:
				return $month;
			break;
		}
	}
	
	private function postWeekDay()
	{
		$request = Request::getInstance();
		$weekDay = NULL;
		
		for($w=0;$w<8;$w++)
		{
		$nameI = 'weekDay_' . $w;
			if (isSet($request->data->$nameI))
			{
				$weekDay .= $w . ',';
			}
		}

		
		$weekDay = trim($weekDay, ',');

		
		switch(true)
		{
			case (strlen($weekDay) >= 13):
				return '*/1';
			break;
			case (strlen($weekDay) == 0):
				return '*';
			break;
			default:
				return $weekDay;
			break;
		}
	}

}

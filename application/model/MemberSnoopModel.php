<?php
Class MemberSnoopModel extends Model {
	public function install()
	{
		$this->query("
		CREATE TABLE  `".__SQL."_MemberSnoop` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`os` VARCHAR( 64 ) NOT NULL ,
		`navigator` VARCHAR( 255 ) NOT NULL ,
		`time` INT( 11 ) NOT NULL ,
		`ip` VARCHAR( 64 ) NOT NULL ,
		`proxy` VARCHAR( 255 ) NOT NULL ,
		`uid` INT NOT NULL ,
		PRIMARY KEY (  `id` )
		) ENGINE = MYISAM ;
		");
	}
	
	
	public function register($uid)
	{
		$this->cleanDb();
		
		$data = new stdClass();
		$data->uid = (int) $uid;
		
		$p = Securite::detect_proxy();
		$data->proxy = $p[1];
		$data->ip = Securite::ipX();
		$data->navigator = $data->os = 'Unknown';
		if (isSet ( $_SERVER ['HTTP_USER_AGENT'] )) {
			switch ($_SERVER ['HTTP_USER_AGENT']) {
				case 'MSIE' :
					$data->navigator = 'Internet explorer';
					break;
				case 'Firefox' :
					$data->navigator = 'Mozilla Firefox';
					break;
				case 'Chrome' :
					$data->navigator = 'Google Chrome';
					break;
				default :
					$data->navigator = $_SERVER ['HTTP_USER_AGENT'];
			}
			
			$data->os = $this->getOS($_SERVER ['HTTP_USER_AGENT']);
		}
		$data->time = time();
		$this->save($data);
		
	}
	
	private function cleanDb()
	{
		$thisTime = time() - 7889231; /* 3 month in secondes */
		$this->query("DELETE FROM `{$this->table}` WHERE `{$this->table}`.`time` < $thisTime");
	}
	
	
	/*
	 * Author: Daniel Kassner
	 * Website: http://www.danielkassner.com
	 */
	private function getOS($userAgent) {
		// Create list of operating systems with operating system name as array
		// key
		
		// expressions as value
		// to identify operating
		// system
		$oses = array (
				'iPhone' => '(iPhone)',
				'Windows 3.11' => 'Win16',
				'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
				'Windows 98' => '(Windows 98)|(Win98)',
				'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
				'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
				'Windows 2003' => '(Windows NT 5.2)',
				'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
				'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
				'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
				'Windows ME' => 'Windows ME',
				'Open BSD' => 'OpenBSD',
				'Sun OS' => 'SunOS',
				'Linux' => '(Linux)|(X11)',
				'Safari' => '(Safari)',
				'Macintosh' => '(Mac_PowerPC)|(Macintosh)',
				'QNX' => 'QNX',
				'BeOS' => 'BeOS',
				'OS/2' => 'OS/2',
				'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)' );
		
		foreach ( $oses as $os => $pattern ) {
			if (preg_match( '#' . $pattern . '#', $userAgent )) {
				return $os;
			}
		}
		return 'Unknown'; // Cannot find operating system so return Unknown
	}
}
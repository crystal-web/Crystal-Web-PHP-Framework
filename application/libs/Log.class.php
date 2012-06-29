<?php
/**
* @title Simple MVC systeme
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @documentation: http://www.crystal-web.org/viki/class-log
*/
Class Log {
private static $log;
private static $color = array();
	
	public static function setLog($logToSet, $type='LOG')
	{
		if (!isset(self::$log)) {
			
			self::$log[] = array('message' => 'Starting service log', 'type' => 'SERVICE'); 
		}
		
		self::$log[] = array('message' => $logToSet, 'type' => strtoupper($type));
	}
	
	public static function getLog()
	{
	return self::$log; 
	}
	
	
	public static function console($printResult = false)
	{
		if (count(self::$log))
		{
			$html = '<ol>';
			foreach (self::$log as $k => $v)
			{
				$html .= '<li><span style="font-weight: bold;color: '.Log::randColor($v['type']).'">[' . $v['type'] . ']</span>&nbsp;' . $v['message'] . '</li>';
			//	$html .= '<li><span style="font-weight: bold;color: '.Log::hexColor($v['type']).'">[' . $v['type'] . ']</span>&nbsp;' . $v['message'] . '</li>';
			} 
			$html .= '</ol>';
			
			if (!$printResult)
			{
			return $html;
			} else { echo $html; }
		}
	}
	
	
	private static function randColor($type)
	{
		if (isSet(Log::$color[$type]))
		{
			return Log::$color[$type];
		}
		else
		{
			$r = rand(0, 200);
			$g = rand(0, 200);
			$b = rand(0, 200);
			
			foreach(Log::$color AS $t => $c)
			{
				if ($c == "rgb($r, $g, $b);")
				{
					return Log::randColor($type);
				}
			}
			
			
			Log::$color[$type] = "rgb($r, $g, $b);";
			return Log::$color[$type];
		}
		
	}
	
}
?>
<?php
class i18n {
	static public $definitions;
	static private $lng;
	static function getLanguage()
	{
		return self::$lng;
	}
	
	static function load($lng, $controller = NULL) {
		self::$lng = $lng;
		
		$LANG = array();
		$error = false;

		$mLanguage = new LanguageModel();
		// Charge le language depuis la base de donnée
		$language = $mLanguage->getLanguage($lng, $controller);
		if ($language)
		{
			Log::setLog('Load: ' . $lng . ' for ' . $controller . ' in database', 'i18n');
			$LANG = unserialize($language->language);;
		}
		else
		{
			$error = true;
		}
		
		// Charge le fichier de lang depuis le dossier
		if (file_exists(__APP_PATH . DS . 'i18n'  . DS . $lng . DS . 'main.php'))
		{
			Log::setLog('Load: ' . __APP_PATH . DS . 'i18n'  . DS . $lng . DS . 'main.php', 'i18n');
			include_once __APP_PATH . DS . 'i18n'  . DS . $lng . DS . 'main.php';
		}
		else
		{
			if ($error)
			{
				$config = Config::getInstance();
				return Router::redirect(Router::urlLanguage($config->getDefaultLanguage()));
			}
		}
		
		if (count($LANG))
		{
			self::$definitions = $LANG;
		}
		
	}

	static function get($key, $values = false) {
		if (isset(self::$definitions[$key])) {
			$def = self::$definitions[$key];
		} else {
			$def = $key;
		}

		//if (!is_array($value)) $value[]=$value;
		return vsprintf($def, $values);
	}
}
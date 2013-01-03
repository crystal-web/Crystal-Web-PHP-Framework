<?php
Class PHPmodule {

/**
 * parse php modules from phpinfo
 *
 * @author code at adspeed dot com
 * @package parsePHP
 * @return array
 */
public function parsePHPModules() {
	ob_start ();
	phpinfo ( INFO_MODULES );
	$s = ob_get_contents ();
	ob_end_clean ();
	
	$s = strip_tags ( $s, '<h2><th><td>' );
	$s = preg_replace ( '/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $s );
	$s = preg_replace ( '/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $s );
	$vTmp = preg_split ( '/(<h2>[^<]+<\/h2>)/', $s, - 1, PREG_SPLIT_DELIM_CAPTURE );
	$vModules = array ();
	for($i = 1; $i < count ( $vTmp ); $i ++) {
		if (preg_match ( '/<h2>([^<]+)<\/h2>/', $vTmp [$i], $vMat )) {
			$vName = trim ( $vMat [1] );
			$vTmp2 = explode ( "\n", $vTmp [$i + 1] );
			foreach ( $vTmp2 as $vOne ) {
				$vPat = '<info>([^<]+)<\/info>';
				$vPat3 = "/$vPat\s*$vPat\s*$vPat/";
				$vPat2 = "/$vPat\s*$vPat/";
				if (preg_match ( $vPat3, $vOne, $vMat )) { // 3cols
					$vModules [$vName] [trim ( $vMat [1] )] = array (trim ( $vMat [2] ), trim ( $vMat [3] ) );
				} elseif (preg_match ( $vPat2, $vOne, $vMat )) { // 2cols
					$vModules [$vName] [trim ( $vMat [1] )] = trim ( $vMat [2] );
				}
			}
		}
	}
	return $vModules;
}


/**
 * get a module setting
 *
 * @author code at adspeed dot com
 * @package parsePHP
 * @return array
 */
public function getModuleSetting($pModuleName, $pSetting) {
	$vModules = $this->parsePHPModules ();
	return $vModules [$pModuleName] [$pSetting];
}

}
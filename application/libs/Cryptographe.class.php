<?php
/**
* @title Simple MVC systeme
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @documentation: http://www.crystal-web.org/viki/class-cryptographe 
*/
class Cryptographe 
{
        
    public function polybeCryper($string, $tab = false) {
    	$string = strtolower($string);
    	if (!$tab) {
        	// Creation des variables mélangés
			$tab = array();
			$tab[0][0] = 'z'; $tab[0][1] = 'y'; $tab[0][2] = 'x'; $tab[0][3] = 'w'; $tab[0][4] = '4'; $tab[0][5] = '5'; $tab[0][6] = '1';
			$tab[1][0] = 'a'; $tab[1][1] = 'e'; $tab[1][2] = 'k'; $tab[1][3] = 'q'; $tab[1][4] = 'b'; $tab[1][5] = 'f'; $tab[1][6] = 'g';
			$tab[2][0] = 'v'; $tab[2][1] = 'p'; $tab[2][2] = 'j'; $tab[2][3] = 'h'; $tab[2][4] = 'n'; $tab[2][5] = 't'; $tab[2][6] = 'o';
			$tab[3][0] = '0'; $tab[3][1] = '9'; $tab[3][2] = '2'; $tab[3][3] = '3'; $tab[3][4] = 'r'; $tab[3][5] = 's'; $tab[3][6] = 'u';
			$tab[4][0] = '8'; $tab[4][1] = '6'; $tab[4][2] = '7'; $tab[4][3] = 'm'; $tab[4][4] = 'l'; $tab[4][5] = 'i'; $tab[4][6] = 'c';
			$tab[5][0] = 'd'; $tab[5][1] = '+'; $tab[5][2] = '-'; $tab[5][3] = '*'; $tab[5][4] = '/'; $tab[5][5] = '='; $tab[5][6] = '%';
			//shuffle($tab);
    	}
    	
		$messageCrypte = NULL;
		$messageDecrypte = NULL;
        
        //cryptage du message :
		for ($w = 0; $w<strlen($string); $w++) {
			for($j = 0; $j<6; $j++) {
				for($k = 0; $k<7; $k++) {
					//echo $w . '|' . $j . '|' . $k.'<br>';
					if (isSet($string[$w]) && isSet($tab[$j][$k])) {
						if ($string[$w] == ' ' && $stop == false) {
							$messageCrypte .= ' ';
							$stop = true;
						}
						
						if($string[$w] == $tab[$j][$k]) {
							$messageCrypte .= $j.$k;
						}
					}
				}
				$stop = false;
			}
		}

        return array($messageCrypte, $tab);
    }

    
    public function polybeDecryper($string, $tab) {
		$messageDecrypte = NULL;
    	for ($w = 0; $w<strlen($string); $w++) {
			$pos = $w;
			$pos++;
			
			if (isSet($string[$w]) && isSet($string[$pos])) {
				if (isSet($tab[$string[$w]][$string[$pos]])) {
					$messageDecrypte .= $tab[$string[$w]][$string[$pos]];
					$w++;
				} else {
					$messageDecrypte .= ' ';
				}
			}
		}
        return array($messageDecrypte, $tab);
    }
    
    
    public function gzencode($phpcode) {
    	return base64_encode( gzcompress($phpcode));
    }
    
    public function gzdecode($gzphpcode) {
    	eval('?>'.gzuncompress(base64_decode($gzphpcode)));
    }
	
	
	/**
	 * Caractère aléatoire alphanumerique
	 *
	 * @link http://crystal-web.org
	 * @author Christophe BUFFET
	 * @param int $nb
	 * @return string
	 */
	function genPassCode($nb = 10) {
		return substr(md5(uniqid()), 0, $nb);
	}
	
	/**
	 * Générateur de login, inutile donc indispensable
	 */
    public function login_generator() {
        $voyelles = array ('a', 'e', 'i', 'o', 'u' );
        shuffle($voyelles)-1;
        $nb_voyelles = count($voyelles);
        
		$consonnes = array ('b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'bl', 'bs', 'xl', 'xk', 'xd', 'ch', 'ck', 'cl', 'cm', 'cn', 'cp', 'cr', 'ct', 'cv', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'dj', 'dg', 'dl', 'dm', 'dn', 'dp', 'dr', 'zd', 'z', 'x', 'fd', 'ff', 'fl', 'fm', 'fp', 'fr', 'ft', 'xt', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'gb', 'gd', 's', 'gh', 'gl', 'gm', 'gn', 'gp', 'gr', 'gs', 'gt', 'zl', 'zb', 'xp', 'kl', 'kr', 'kt', 'll', 'lb', 'lc', 'lck', 'ld', 'lch', 'lf', 'lg', 'lk', 'lm', 'ln', 'lp', 'lr', 'ls', 'lt', 'lv', 'lqu', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'mm', 'mb', 'mc', 'md', 'mg', 'mk', 'ml', 'mn', 'mp', 'mr', 'ms', 'mt', 'z', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'nn', 'nc', 'nd', 'nf', 'ng', 'nj', 'nk', 'nl', 'nr', 'ns', 'nt', 'nz', 'nqu', 't', 'qu', 'pf', 'pp', 'ph', 'pj', 'y', 'pl', 'pm', 'pn', 'ps', 'pt', 'v', 'b', 'c', 'd', 'f', 'g', 'j', 'xb', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'rb', 'rc', 'rd', 'rf', 'rg', 'rk', 'rl', 'rm', 'rn', 'rp', 'rqu', 'rr', 'rs', 'rv', 'ss', 'sb', 'sc', 'sd', 'squ', 'sl', 'sm', 'sn', 'sp', 'st', 'sv', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'tt', 'tch', 'tf', 'br', 'tm', 'tr', 'ts', 'r', 'tz', 'zn', 'zp', 'vl', 'vl', 'gu', 'zt', 'vr', 'gh', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p' );
		shuffle($consonnes);
		$nb_consones = count($consonnes)-1;
		
		$consonnes_start = array ('b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x', 'z', 'y', 'qu', 'gu', 'gh', 'bl', 'gh', 'br', 'ch', 'cl', 'cr', 'dr', 'fl', 'fr', 'ph', 'gl', 'gr', 'h', 'kr', 'kl', 'pr', 'pl', 'v', 'sc', 't', 'squ', 'sl', 'sm', 'sp', 'st', 'tch', 'tr', 'ts', 'vr', 'vl', 'b', 'c', 'd', 'f', 'g', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's' );
		shuffle($consonnes_start);
		
		$consonnes_end=array('b','c','d','ff','tch','ch','ck','l','m','n','ppe','r','s','t','ve','x','z','y','que','gue','gh','ct','on','an','a','o','e','en','lle','rre','sse','ne','mme','ffe','tte','ine');
		shuffle($consonnes_end);

		//$tmp = $consonnes_start[0] . $voyelles[rand(0,$nb_voyelles)] . $consonnes[rand(0,$nb_consones)] .$voyelles[rand(0,$nb_voyelles)] . $consonnes_end[0];

		$tmp =$voyelles[rand(0,$nb_voyelles)] . $consonnes_start[0] . $voyelles[rand(0,$nb_voyelles)] . $consonnes[rand(0,$nb_consones)] . $consonnes_end[0];
		
		$patterns		= array('/quu/','/ik/','/ak/','/uc/','/aa/','/uu/','/ii/','/iy/','/ckr/','/xki/','/xka/','/xku/','/xko/');
		$replacements	= array('cku','ick','ack','uck','a','u','i', 'y','kr','sky', 'ska','sku','sko');
		preg_replace($patterns, $replacements, $tmp);
    	return $tmp;
    }
}
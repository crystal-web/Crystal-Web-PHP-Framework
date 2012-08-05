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
        
        public function polybeCryper($string, $tab = false)
        {
        	$string = strtolower($string);
        	if (!$tab)
        	{
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
			for ($w = 0; $w<strlen($string); $w++)
			{
				for($j = 0; $j<6; $j++)
				{
					for($k = 0; $k<7; $k++)
					{
						//echo $w . '|' . $j . '|' . $k.'<br>';
						if (isSet($string[$w]) && isSet($tab[$j][$k]))
						{
							if ($string[$w] == ' ' && $stop == false)
							{
								var_Dump($string[$w]);
								$messageCrypte .= ' ';
								$stop = true;
							}
							
							if($string[$w] == $tab[$j][$k])
							{
								$messageCrypte .= $j.$k;
							}



						}



					}
					$stop = false;
				}
			}

            return array($messageCrypte, $tab);
        }

        
        public function polybeDecryper($string, $tab)
        {
			$messageDecrypte = NULL;
            
            
            
        	for ($w = 0; $w<strlen($string); $w++)
			{

				
				$pos = $w;
				$pos++;
				
				if (isSet($string[$w]) && isSet($string[$pos]))
				{
					if (isSet($tab[$string[$w]][$string[$pos]]))
					{
						$messageDecrypte .= $tab[$string[$w]][$string[$pos]];
						$w++;
					}
					else 
					{
						$messageDecrypte .= ' ';
					}
					
				}

			
			}
            
            return array($messageDecrypte, $tab);
        }
        
        
        public function gzencode($phpcode)
        {
        	return base64_encode( gzcompress($phpcode));
        }
        
        public function gzdecode($gzphpcode)
        {
        	eval('?>'.gzuncompress(base64_decode($gzphpcode)));
        }
        
    }
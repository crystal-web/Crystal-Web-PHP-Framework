<?php
/*
 * BETA
 * User Contributed Notes openssl_public_encrypt
 * sumadhuracool at gmail dot com
 */
class openssl { 
# generate a 1024 bit rsa private key, ask for a passphrase to encrypt it and save to file 
//openssl genrsa -des3 -out /path/to/privatekey 1024 
  
# generate the public key for the private key and save to file 
//openssl rsa -in /path/to/privatekey -pubout -out /path/to/publickey 
//programatically using php-openssll 
	public function __construct() {
		if (!is_dir(__APP_PATH . "/cache/ssl")) {
			mkdir(__APP_PATH . "/cache/ssl");
			mkdir(__APP_PATH . "/cache/ssl/private");
			mkdir(__APP_PATH . "/cache/ssl/public");
			mkdir(__APP_PATH . "/cache/ssl/csr");
		}
	}
	//This will call while registration 
	function gen_cert($userid) { 
		$dn = array(
			"countryName" => 'XX',
			"stateOrProvinceName" => 'State',
			"localityName" => 'SomewhereCity',
			"organizationName" =>'MySelf',
			"organizationalUnitName" => 'Whatever',
			"commonName" => 'mySelf',
			"emailAddress" => 'user@example.com');

		$numberofdays = 365; 
		//RSA encryption and 1024 bits length 
		$privkey = openssl_pkey_new(array(
			'private_key_bits' => 1024,
			'private_key_type' => OPENSSL_KEYTYPE_RSA)); 
		$csr = openssl_csr_new($dn, $privkey);
		$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);
		openssl_x509_export($sscert, $publickey);
		openssl_pkey_export($privkey, $privatekey, magicword);
		openssl_csr_export($csr, $csrStr); 
		
		//Generated keys are stored into files 		
		file_put_contents(__APP_PATH . "/cache/ssl/private/$userid.key", $privatekey, LOCK_EX); 
		file_put_contents(__APP_PATH . "/cache/ssl/public/$userid.crt", $publickey, LOCK_EX);
		file_put_contents(__APP_PATH . "/cache/ssl/csr/$userid.csr", $csrStr, LOCK_EX); 
	}


	//Encryption with public key 
	function encrypt($source,$pub_key = false) {
		if (!$pub_key) { 
		//path holds the certificate path present in the system                
		$path=__APP_PATH . "/cache/ssl/public/server.crt";
			if (!file_exists($path)) {
				$this->gen_cert('server');
			}
		$pub_key=file_get_contents($path);
		}
		openssl_get_publickey($pub_key);
		
		$j=0;
		$x=strlen($source)/10;
		$y=floor($x);
		$crt=null;
			for($i=0;$i<$y;$i++) {
				$crypttext='';
				openssl_public_encrypt(substr($source,$j,10),$crypttext,$pub_key);$j=$j+10; 
				$crt.=$crypttext;
				$crt.=":::";
			}
			
			if((strlen($source)%10)>0) { 
				openssl_public_encrypt(substr($source,$j),$crypttext,$pub_key); 
				$crt.=$crypttext; 
			}
		return($crt);
	}


	//Decryption with private key 
	function decrypt($crypttext,$priv_key=false) {
		
		if (!$priv_key) { 
		//path holds the certificate path present in the system                
		$path=__APP_PATH . "/cache/ssl/private/server.key";
			if (!file_exists($path)) {
				$this->gen_cert('server');
			}
		$priv_key=file_get_contents($path);
		}
		
$res = openssl_get_privatekey($priv_key,magicword);
/*
 * NOTE:  Here you use the returned resource value
 */
openssl_private_decrypt($crypttext,$newsource,$res);
echo "String decrypt : $newsource";

		return;
		//$res1= openssl_get_privatekey($priv_key,magicword);
		debug($res1);
		$tt=explode(":::",$crypttext);
		$cnt=count($tt);
		$i=0;
		$str=null;
		while($i<$cnt) 
		{ 
		openssl_private_decrypt($tt[$i],$str1,$res1);
		$str.=$str1;
		$i++;
		} 
		return $str;
	}


	function sign($source,$rc) { 
		$has=sha1($source);
		$source.="::";
		$source.=$has;
		$path=__APP_PATH . "/cache/ssl/public/$rc.crt";
		$fp=fopen($path,"r"); 
		$pub_key=fread($fp,8192);
		fclose($fp);
		openssl_get_publickey($pub_key);
		openssl_public_encrypt($source,$mese,$pub_key);
		return $mese;
	}


	function verify($crypttext,$userid) { 
		$path=__APP_PATH . "/cache/ssl/private/$userid.key";
		$fpp1=fopen($path,"r");
		$priv_key=fread($fpp1,8192);
		fclose($fpp1);
		$res1= openssl_get_privatekey($priv_key,magicword);
		openssl_private_decrypt($crypttext,$has1,$res1);
		list($c1,$c2)=split("::",$has1);
		$has=sha1($c1);
		
		if($has==$c2) 
		{ 
			$message=$c1;
			return $message;
		}                 
	}
	
	function getDetails($userid='server') {
		
		//path holds the certificate path present in the system                
		$path=__APP_PATH . "/cache/ssl/private/" . $userid.".key";
			if (!file_exists($path)) {
				$this->gen_cert($userid);
			}
		$priv_key=file_get_contents($path);
		
		
		debug(openssl_pkey_get_public($priv_key));
		return openssl_pkey_get_details(openssl_pkey_get_public($priv_key));
	}
}
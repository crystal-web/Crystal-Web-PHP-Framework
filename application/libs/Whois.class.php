<?PHP
/**
 * @category ServerTools
 * @package Whois
 * @author Peter Schmalfeldt <peter@manifestinteractive.com>
 * @link http://www.manifestinteractive.com
 */

/**
 * Begin Document
 */ 

class Whois {
	
	/**
     * fsockopen port
     *
     * @var    string
     * @access private
     */
	private $port = 43;
	
	/**
     * fsockopen Error Number
     *
     * @var    string
     * @access private
     */
	private $errno;
	
	/**
     * fsockopen Error String
     *
     * @var    string
     * @access private
     */
	private $errstr;
	
	/**
     * fsockopen Time Out
     *
     * @var    string
     * @access private
     */
	private $timeout = 10;
	
	/**
     * Selected Server from $whoisservers based on TLD from $domain
     *
     * @var    string
     * @access private
     */
	private $whoisserver;
	
	/**
     * Available Whois Servers
     *
     * @var    array
     * @access private
     */
	private $whoisservers = array(
		"ac" 	=> "whois.nic.ac",
		"ae" 	=> "whois.nic.ae",
		"aero"	=> "whois.aero",
		"af" 	=> "whois.nic.af",
		"ag" 	=> "whois.nic.ag",
		"al" 	=> "whois.ripe.net",
		"am" 	=> "whois.amnic.net",
		"arpa" 	=> "whois.iana.org",
		"as" 	=> "whois.nic.as",
		"asia" 	=> "whois.nic.asia",
		"at" 	=> "whois.nic.at",
		"au" 	=> "whois.aunic.net",
		"az" 	=> "whois.ripe.net",
		"ba" 	=> "whois.ripe.net",
		"be" 	=> "whois.dns.be",
		"bg" 	=> "whois.register.bg",
		"bi" 	=> "whois.nic.bi",
		"biz" 	=> "whois.biz",
		"bj" 	=> "whois.nic.bj",
		"br" 	=> "whois.registro.br",
		"bt" 	=> "whois.netnames.net",
		"by" 	=> "whois.ripe.net",
		"bz" 	=> "whois.belizenic.bz",
		"ca" 	=> "whois.cira.ca",
		"cat" 	=> "whois.cat",
		"cc" 	=> "whois.nic.cc",
		"cd" 	=> "whois.nic.cd",
		"ch" 	=> "whois.nic.ch",
		"ci" 	=> "whois.nic.ci",
		"ck" 	=> "whois.nic.ck",
		"cl" 	=> "whois.nic.cl",
		"cn" 	=> "whois.cnnic.net.cn",
		"com" 	=> "whois.verisign-grs.com",
		"coop" 	=> "whois.nic.coop",
		"cx" 	=> "whois.nic.cx",
		"cy" 	=> "whois.ripe.net",
		"cz" 	=> "whois.nic.cz",
		"de" 	=> "whois.denic.de",
		"dk" 	=> "whois.dk-hostmaster.dk",
		"dm" 	=> "whois.nic.cx",
		"dz" 	=> "whois.ripe.net",
		"edu" 	=> "whois.educause.edu",
		"ee" 	=> "whois.eenet.ee",
		"eg" 	=> "whois.ripe.net",
		"es" 	=> "whois.ripe.net",
		"eu" 	=> "whois.eu",
		"fi" 	=> "whois.ficora.fi",
		"fo" 	=> "whois.ripe.net",
		"fr" 	=> "whois.nic.fr",
		"gb" 	=> "whois.ripe.net",
		"gd" 	=> "whois.adamsnames.com",
		"ge" 	=> "whois.ripe.net",
		"gg" 	=> "whois.channelisles.net",
		"gi" 	=> "whois2.afilias-grs.net",
		"gl" 	=> "whois.ripe.net",
		"gm" 	=> "whois.ripe.net",
		"gov" 	=> "whois.nic.gov",
		"gr" 	=> "whois.ripe.net",
		"gs" 	=> "whois.nic.gs",
		"gw" 	=> "whois.nic.gw",
		"gy" 	=> "whois.registry.gy",
		"hk" 	=> "whois.hkirc.hk",
		"hm" 	=> "whois.registry.hm",
		"hn" 	=> "whois2.afilias-grs.net",
		"hr" 	=> "whois.ripe.net",
		"hu" 	=> "whois.nic.hu",
		"ie" 	=> "whois.domainregistry.ie",
		"il" 	=> "whois.isoc.org.il",
		"in" 	=> "whois.inregistry.net",
		"info" 	=> "whois.afilias.info",
		"int" 	=> "whois.iana.org",
		"io" 	=> "whois.nic.io",
		"iq" 	=> "vrx.net",
		"ir" 	=> "whois.nic.ir",
		"is" 	=> "whois.isnic.is",
		"it" 	=> "whois.nic.it",
		"je" 	=> "whois.channelisles.net",
		"jobs" 	=> "jobswhois.verisign-grs.com",
		"jp" 	=> "whois.jprs.jp",
		"ke" 	=> "whois.kenic.or.ke",
		"kg" 	=> "www.domain.kg",
		"ki" 	=> "whois.nic.ki",
		"kr" 	=> "whois.nic.or.kr",
		"kz" 	=> "whois.nic.kz",
		"la" 	=> "whois.nic.la",
		"li" 	=> "whois.nic.li",
		"lt" 	=> "whois.domreg.lt",
		"lu" 	=> "whois.dns.lu",
		"lv" 	=> "whois.nic.lv",
		"ly" 	=> "whois.nic.ly",
		"ma" 	=> "whois.iam.net.ma",
		"mc" 	=> "whois.ripe.net",
		"md" 	=> "whois.ripe.net",
		"me" 	=> "whois.meregistry.net",
		"mg" 	=> "whois.nic.mg",
		"mil" 	=> "whois.nic.mil",
		"mn" 	=> "whois.nic.mn",
		"mobi" 	=> "whois.dotmobiregistry.net",
		"ms" 	=> "whois.adamsnames.tc",
		"mt" 	=> "whois.ripe.net",
		"mu" 	=> "whois.nic.mu",
		"museum"=> "whois.museum",
		"mx" 	=> "whois.nic.mx",
		"my" 	=> "whois.mynic.net.my",
		"na" 	=> "whois.na-nic.com.na",
		"name" 	=> "whois.nic.name",
		"net" 	=> "whois.verisign-grs.net",
		"nf" 	=> "whois.nic.nf",
		"nl" 	=> "whois.domain-registry.nl",
		"no" 	=> "whois.norid.no",
		"nu" 	=> "whois.nic.nu",
		"nz" 	=> "whois.srs.net.nz",
		"org" 	=> "whois.pir.org",
		"pl" 	=> "whois.dns.pl",
		"pm" 	=> "whois.nic.pm",
		"pr" 	=> "whois.uprr.pr",
		"pro" 	=> "whois.registrypro.pro",
		"pt" 	=> "whois.dns.pt",
		"re" 	=> "whois.nic.re",
		"ro" 	=> "whois.rotld.ro",
		"ru" 	=> "whois.ripn.net",
		"sa" 	=> "whois.nic.net.sa",
		"sb" 	=> "whois.nic.net.sb",
		"sc" 	=> "whois2.afilias-grs.net",
		"se" 	=> "whois.iis.se",
		"sg" 	=> "whois.nic.net.sg",
		"sh" 	=> "whois.nic.sh",
		"si" 	=> "whois.arnes.si",
		"sk" 	=> "whois.ripe.net",
		"sm" 	=> "whois.ripe.net",
		"st" 	=> "whois.nic.st",
		"su" 	=> "whois.ripn.net",
		"tc" 	=> "whois.adamsnames.tc",
		"tel" 	=> "whois.nic.tel",
		"tf" 	=> "whois.nic.tf",
		"th" 	=> "whois.thnic.net",
		"tj" 	=> "whois.nic.tj",
		"tk" 	=> "whois.dot.tk",
		"tl" 	=> "whois.nic.tl",
		"tm" 	=> "whois.nic.tm",
		"tn" 	=> "whois.ripe.net",
		"to" 	=> "whois.tonic.to",
		"tp" 	=> "whois.nic.tl",
		"tr" 	=> "whois.nic.tr",
		"travel"=> "whois.nic.travel",
		"tv" 	=> "tvwhois.verisign-grs.com",
		"tw" 	=> "whois.twnic.net.tw",
		"ua" 	=> "whois.net.ua",
		"ug" 	=> "whois.co.ug",
		"uk" 	=> "whois.nic.uk",
		"us" 	=> "whois.nic.us",
		"uy" 	=> "nic.uy",
		"uz" 	=> "whois.cctld.uz",
		"va" 	=> "whois.ripe.net",
		"vc" 	=> "whois2.afilias-grs.net",
		"ve" 	=> "whois.nic.ve",
		"vg" 	=> "whois.adamsnames.tc",
		"wf" 	=> "whois.nic.wf",
		"ws" 	=> "whois.website.ws",
		"yt" 	=> "whois.nic.yt",
		"yu" 	=> "whois.ripe.net",
		"xxx"	=> "whois.nic.xxx"
	);
		
	/**
     * Domain Name (URL or IP Address)
     *
     * @var    string
     * @access public
     */
	public $domain;
	
	/**
     * Full URL
     *
     * @var    string
     * @access public
     */
	public $url;
	
	/**
     * Domain Array containing Domain and TLD
     *
     * @var    array
     * @access public
     */
	public $domainarray;
	
	/**
     * Returned Whois Text
     *
     * @var    string
     * @access public
     */
	public $data;
	
	/**
     * Constructor
	 *
	 * Create Google Translate Object and Convert $string
	 * <code>
	 * <?php
	 * $whois = new Whois("example.com");
	 * echo $whois->domain;					// 'example.com'
	 * echo $whois->url; 					// 'http://example.com'
	 * echo $whois->domainarray[0];			// 'com'
	 * echo $whois->domainarray[1];			// 'example'
	 * echo $whois->data; 					// [ ... whois data ... ] 
	 *
	 * $whoisFull = new Whois("http://www.example.com");
	 * echo $whoisFull->domain;				// 'example.com'
	 * echo $whoisFull->url; 				// 'http://www.example.com'
	 * echo $whoisFull->domainarray[0];		// 'com'
	 * echo $whoisFull->domainarray[1];		// 'example'
	 * echo $whoisFull->data; 				// [ ... whois data ... ] 
	 *
	 * $whoisIP = new Whois("123.45.678.90");
	 * echo $whoisIP->domain;				// '123.45.678.90'
	 * echo $whoisIP->url; 					// 'http://123.45.678.90'
	 * echo $whoisIP->domainarray[0];		// NULL
	 * echo $whoisIP->domainarray[1];		// NULL
	 * echo $whoisIP->data; 				// [ ... whois data ... ] 
	 * ?>
 	 * </code>
     *
     * @param 	string $domain URL or IP Address
     * @access 	public
     */
	
	function __construct($domain){
		
		$this->domain = $domain;
		$this->domain = rtrim($this->domain, "/");
		$this->domain = preg_replace('/http:\/\//', '', $this->domain);		
		$this->url = (substr($this->domain,0,7) != 'http://') ? 'http://'.$this->domain:$this->domain;
		
		if(!preg_match('/(\d+).(\d+).(\d+).(\d+)/', $this->domain)){
			$this->domainarray = preg_split("#\.#", $this->domain);
			if(count($this->domainarray)==1) $this->domainarray[] = 'com';
			$this->domainarray = array_reverse($this->domainarray);
			$this->domain = $this->domainarray[1].'.'.$this->domainarray[0];
		}
		
		if(strlen($this->domain)>0){
			foreach($this->whoisservers as $tld=>$server) {
				if(substr($this->domain, -strlen($tld)) == $tld) {
					$this->whoisserver = $server;
					break;
				}
			}
			if(!$this->whoisserver && preg_match('/(\d+).(\d+).(\d+).(\d+)/', $this->domain)){
				$this->whoisserver = "whois.arin.net";
			}
			else if(!$this->whoisserver && !preg_match('/(\d+).(\d+).(\d+).(\d+)/', $this->domain)) {
				$this->data = "Error: No appropriate Whois server found for {$this->domain} domain!";
			}
			
			if($result = $this->queryServer()) {
				preg_match("/Whois Server: (.*)/", $result, $matches);
				$secondary = $matches[1];
				if($secondary) {
					$this->whoisserver = $secondary;
					$result = $this->queryServer();
				}
				$this->data = $result;
			}
			else {
				$this->data = "Error: No results retrieved from $whoisserver server for {$this->domain} domain!";
			}
		}
	}
	
	/**
     * Query Selected Whois Server
     *
     * @access private
     */
	private function queryServer(){
		$out = "";
		$fp = @fsockopen($this->whoisserver, $this->port, $this->errno, $this->errstr, $this->timeout);
		debug($fp);
		if($fp){
			fputs($fp, $this->domain . "\r\n");
			while(!feof($fp)) $out .= fgets($fp);
			fclose($fp);
			if(strlen($out)>0) return $out;
			else return false;
		}
		else {
			return false;
		}
	}
}
?>
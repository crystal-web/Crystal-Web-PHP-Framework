<?php
Class rpcController extends Controller {
	
	public function index()
	{
		if (!isSet($this->mvc->Request->params['cmd']))
		{
			$this->mvc->Page->setBreadcrumb($this->controller,  ucfirst($this->controller) );
			require __APP_PATH . DS . 'controller' . DS . 'errorController.php';
			$c = new errorController($this->mvc);
			$c->e404();
			die;
		}
		$method = clean( $this->mvc->Request->params['cmd'], 'alpha' );
		return $this->$method();
	}
	
	public function __call($method, $arguments)
	{
		$this->mvc->Page->setLayout('empty');	
		$this->mvc->Plugin->triggerEvents('rpc' . $method);
	}
	
	
	public function devphp()
	{
		$this->mvc->Page->setLayout('empty');
		echo '∂єνρнρ ٩(×̯X)۶ PӨRNӨ★STΔЯ 123';
	}


	public function findmember()
	{
		$this->mvc->Page->setLayout('empty');
		
		// Is there a posted query string?
	    if(isset($this->mvc->Request->data->queryString)) {
	        $queryString = $this->mvc->Request->data->queryString;
	        // Is the string length greater than 0?
	        if(strlen($queryString) > 0) {                                                           
	
	        $m = loadModel('Member');
	        $resp = $m->find(array('conditions' => array('loginmember' => $queryString.'%'), 'limit' => '5'));
	        if($resp) {
	            // While there are results loop through them - fetching an Object (i like PHP5 btw!).
	            foreach($resp AS $k => $v)
	            {
	                // Format the results, im using <li> for the list, you can change it.
	                // The onClick function fills the textbox with the result.
	               // echo '</li>
				echo '<li onclick="fill(\''.clean($v->loginmember, 'slug').'\');">'.clean($v->loginmember, 'slug').'</li>';
	            }
	        } else { 
	            echo '<li>Aucune correspondance</li>';
	        }
	    } else {
	        // Dont do anything.
	    } // There is a queryString.

		}

	}

	public function grid($mvc)
	{
		header('Content-Type: text/css; charset=UTF-8');
		// Dans l'url, se trouve peut-etre un paramettre
		$url = $mvc->getUrl();
		$url = explode('/', $url);
		$url = end($url);
		
		// Si le paramettre est different de grid
		if ($url != 'grid')
		{
			// Si on trouve "-" alors il y a deux paramettres
			if (stristr($url, '-'))
			{
				$params = explode('-', $url);
				$container = (isSet($params[0])) ? clean( $params[0], 'slug') : 'container';
				$cols = (int) (isSet($params[1])) ? $params[1] : 9;
			} else {
				$container = clean( $url, 'slug');
				$cols = 9;
			}
			
		} else {
			$container = 'container';
			$cols = 9;
		}
		

		$container = (strlen($container) > 1) ? $container : 'container';
		
		$grid = ".".$container."_".$cols." {width: 92%;margin-left: 4%;margin-right: 4%;}" . PHP_EOL;
		
		

		// Grid base
		for ($i=1; $i<($cols+1); $i++)
		{
			$grid .= ".grid_" . $i . ",";
		}
		$grid = substr($grid, 0, -1);
		$grid .= " {display:inline;float: left;position: relative;margin-left: 1%;margin-right: 1%;}" . PHP_EOL;
		
		// Alpha omega
		$grid .= ".alpha {margin-left: 0;}" . PHP_EOL . ".omega {margin-right: 0;}" . PHP_EOL;
		
		
		
		$p = 100;
		$pa = round($p / $cols, 3);
		// Grid
		for ($i=1; $i<($cols+1); $i++)
		{
			$grid .= "." . $container . "_".$cols." .grid_".$i." {width:".(($pa*$i)-2)."%;}" . PHP_EOL;
		}

		// Prefix
		for ($i=1; $i<$cols; $i++)
		{
			$grid .= "." . $container . "_".$cols." .prefix_".$i." {padding-left:".($pa*$i)."%;}" . PHP_EOL;
		}
		
		// Suffix
		for ($i=1; $i<$cols; $i++)
		{
			$grid .= "." . $container . "_".$cols." .suffix_".$i." {padding-right:".($pa*$i)."%;}" . PHP_EOL;
		}
		
		// Push
		for ($i=1; $i<$cols; $i++)
		{
			$grid .= "." . $container . "_".$cols." .push_".$i." {left:".($pa*$i)."%;}" . PHP_EOL;
		}
		
		// Pull
		for ($i=1; $i<$cols; $i++)
		{
			$grid .= "." . $container . "_".$cols." .pull_".$i." {left:-".($pa*$i)."%;}" . PHP_EOL;
		}
		
		
		echo $grid . ".clear {clear: both;display: block;overflow: hidden;visibility: hidden;width: 0;height: 0;}" . PHP_EOL . ".clearfix:after {clear: both;content: ' ';display: block;font-size: 0;line-height: 0;visibility: hidden;width: 0;height: 0;}" . PHP_EOL . ".clearfix {display: inline-block;}" . PHP_EOL . "* html .clearfix {height: 1%;}.clearfix {display: block;}" . PHP_EOL;
		die;
	}
	
	
	public function opensource()
    {
        echo exec("find ../ -type f -name '*.php' -exec wc -l {} \; | awk '{sum+=$1}END{print sum}'");
        die;
    }
	
    public function opensourcepic()
    {
        $sum = exec("find ../ -type f -name '*.php' -exec wc -l {} \; | awk '{sum+=$1}END{print sum}'");
		$im = new CoolPic();
		$im->about(number_format($sum, 0) . ' lignes de code')->show();
        die;
    }
	
}

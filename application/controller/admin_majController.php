<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nc-nd/3.0/
*/
if ($_SESSION['user']['power_level'] != 5)
{
header('Location: '.url('index.php?module=login&action=index'));
exit();
}

class admin_majController Extends baseController {

	public function index()
	{
	rmdir_recursive('files/tmp');
	mkdir('files/tmp');
		echo "Maintenance &eacute;ffectu&eacute;";
	}


}
?>
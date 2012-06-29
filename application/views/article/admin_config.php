<?php
/**
* @title Article Configuration
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Configuration du module article
*/
?>


<form action="" method="post">
<fieldset>
	<legend>Configuration Général</legend>
<?php
echo $form;
?>
</fieldset>
<fieldset>
	<legend>Editorial</legend>
<?php
echo $editoriel . 
$this->mvc->Form->input('submit','Enregister', array('type' => 'submit'));
?>
</fieldset>
</form>
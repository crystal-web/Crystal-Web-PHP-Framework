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
<div class="widget">		
	<div class="widget-header"><h3>Configuration Général</h3></div>
	<div class="widget-content">
	<?php
	echo $form;
	?>
	</div>
</div>


<div class="widget">		
	<div class="widget-header"><h3>Editorial</h3></div>
	<div class="widget-content">
<?php
echo $editoriel; ?>
	</div>
</div>

<?php
echo $this->mvc->Form->input('submit','Enregister', array('type' => 'submit'));
?>
</form>
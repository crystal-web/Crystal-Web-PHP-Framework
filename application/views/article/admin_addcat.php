<form method="post" enctype="multipart/form-data">
<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description , array('required' => 'required')
*/

echo $this->mvc->Form->input('categorie', 'Nom de la cat&eacute;gorie: ');
echo $this->mvc->Form->input('description', 'Description: ', array('type'=>'textarea', 'editor' => ''));
echo $this->mvc->Form->input('submit', 'Enregistrer ', array('type' => 'submit', 'class' => 'btn primary'));
?>
</form>
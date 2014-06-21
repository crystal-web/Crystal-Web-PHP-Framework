<div class="well well-small">
<form enctype="multipart/form-data" class="form-horizontal"  method="post" role="form">
<?php
$form = Form::getInstance();
echo $form->input('title', 'Titre');
echo $form->file('file', 'Image', array('help' => 'Taille maximum: ' . ini_get("upload_max_filesize")));
// echo $form->checkbox('slider', '', array('slide' => 'Afficher sur le slider'));
echo $form->input('content', '', array('type' => 'textarea', 'editor' => 'bbcode'));
echo $form->submit('submit', 'Enregistrer');
?>
</form>
</div>